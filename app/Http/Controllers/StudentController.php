<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\ClassEnrollment;
use App\Models\Submission;
use App\Models\SubmissionHistory;
use App\Models\CalendarIntegration;
use App\Helpers\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentController extends Controller
{
    // ── Overview / Home ───────────────────────────────────────────────────────

    public function overview()
    {
        $student = Auth::user();
        
        // Get enrolled class IDs
        $classIds = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_id');

        // Submitted or graded assignment IDs
        $submittedAssignmentIds = Submission::where('student_id', $student->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->pluck('assignment_id');

        // Upcoming Deadlines (not submitted yet, due in the future)
        $upcomingDeadlines = Assignment::with('academicClass.subject')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Overdue Tasks (not submitted yet, due in the past)
        $overdueTasks = Assignment::with('academicClass.subject')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '<', Carbon::now())
            ->orderBy('due_date', 'desc')
            ->limit(5)
            ->get();

        // Recently Graded
        $recentlyGraded = Submission::with('assignment.academicClass.subject')
            ->where('student_id', $student->id)
            ->where('status', 'graded')
            ->orderBy('graded_at', 'desc')
            ->limit(5)
            ->get();

        // Quick Stats calculations
        $submittedCount = Submission::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        $gradedCount = Submission::where('student_id', $student->id)
            ->where('status', 'graded')
            ->count();

        $overdueCount = Assignment::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '<', Carbon::now())
            ->count();

        $pendingCount = Assignment::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '>=', Carbon::now())
            ->count();

        return view('student.overview', compact(
            'upcomingDeadlines',
            'overdueTasks',
            'recentlyGraded',
            'submittedCount',
            'pendingCount',
            'overdueCount',
            'gradedCount'
        ));
    }

    // ── My Assignments ────────────────────────────────────────────────────────

    public function assignments(Request $request)
    {
        $student = Auth::user();

        $classIds = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_id');

        $query = Assignment::with(['academicClass.subject', 'submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->whereIn('class_id', $classIds)
            ->where('is_published', true);

        // Optional filtering by status
        $statusFilter = $request->input('status');
        if ($statusFilter) {
            if ($statusFilter === 'submitted') {
                $query->whereHas('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id)->where('status', 'submitted');
                });
            } elseif ($statusFilter === 'graded') {
                $query->whereHas('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id)->where('status', 'graded');
                });
            } elseif ($statusFilter === 'pending') {
                $query->whereDoesntHave('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id)->whereIn('status', ['submitted', 'graded']);
                })->where('due_date', '>=', Carbon::now());
            } elseif ($statusFilter === 'overdue') {
                $query->whereDoesntHave('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id)->whereIn('status', ['submitted', 'graded']);
                })->where('due_date', '<', Carbon::now());
            }
        }

        $assignments = $query->orderBy('due_date', 'desc')->paginate(15)->withQueryString();

        // Aggregate counts for the status badges
        $totalCount = Assignment::whereIn('class_id', $classIds)->where('is_published', true)->count();
        
        $submittedAssignmentIds = Submission::where('student_id', $student->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->pluck('assignment_id');

        $pendingCount = Assignment::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '>=', Carbon::now())
            ->count();

        $submittedCount = Submission::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        $gradedCount = Submission::where('student_id', $student->id)
            ->where('status', 'graded')
            ->count();

        $overdueCount = Assignment::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '<', Carbon::now())
            ->count();

        return view('student.assignments', compact(
            'assignments',
            'statusFilter',
            'totalCount',
            'pendingCount',
            'submittedCount',
            'gradedCount',
            'overdueCount'
        ));
    }

    // ── Assignment Detail ─────────────────────────────────────────────────────

    public function assignmentDetail(Assignment $assignment)
    {
        $student = Auth::user();

        $isEnrolled = ClassEnrollment::where('student_id', $student->id)
            ->where('class_id', $assignment->class_id)
            ->where('status', 'active')
            ->exists();

        if (!$isEnrolled || !$assignment->is_published) {
            abort(403, 'You do not have access to this assignment.');
        }

        $assignment->load(['academicClass.subject', 'teacher']);
        
        $submission = Submission::where('student_id', $student->id)
            ->where('assignment_id', $assignment->id)
            ->first();

        // Get submission action history
        $submissionHistories = collect();
        if ($submission) {
            $submissionHistories = SubmissionHistory::where('submission_id', $submission->id)
                ->orderBy('action_at', 'desc')
                ->get();
        }

        return view('student.assignment_detail', compact('assignment', 'submission', 'submissionHistories'));
    }

    // ── Submit Assignment ─────────────────────────────────────────────────────

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $student = Auth::user();

        $isEnrolled = ClassEnrollment::where('student_id', $student->id)
            ->where('class_id', $assignment->class_id)
            ->where('status', 'active')
            ->exists();

        if (!$isEnrolled) abort(403);

        // Check if deadline passed and late submissions not allowed
        if (!$assignment->allow_late && Carbon::now()->isAfter($assignment->due_date)) {
            return back()->with('error', 'The deadline for this assignment has passed.');
        }

        $rules = [];
        if ($assignment->type === 'pdf_upload') {
            $rules['file'] = 'required|file|mimes:pdf|max:10240';
        } elseif ($assignment->type === 'code_submission') {
            $rules['code_content'] = 'required|string';
        } else {
            $rules['file'] = 'nullable|file|mimes:pdf,zip|max:10240';
            $rules['code_content'] = 'nullable|string';
        }

        $data = $request->validate($rules);

        $submission = Submission::firstOrNew([
            'student_id' => $student->id,
            'assignment_id' => $assignment->id,
        ]);

        if ($submission->status === 'graded') {
            return back()->with('error', 'You cannot resubmit a graded assignment.');
        }

        $isResubmission = $submission->exists && $submission->status !== 'unsubmitted';

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
            $submission->file_url = $path;
        }

        if ($request->filled('code_content')) {
            $submission->code_content = $data['code_content'];
        }

        $submission->status = 'submitted';
        $submission->submitted_at = Carbon::now();
        $submission->is_late = Carbon::now()->isAfter($assignment->due_date);
        $submission->save();

        // Write history record
        SubmissionHistory::create([
            'submission_id' => $submission->id,
            'file_url'      => $submission->file_url,
            'code_content'  => $submission->code_content,
            'action'        => $isResubmission ? 'resubmitted' : 'submitted',
            'action_at'     => Carbon::now(),
        ]);

        AuditLogger::log('Assignment Submitted', "Student {$student->name} submitted {$assignment->title}.", $student->id);

        return back()->with('success', 'Assignment submitted successfully.');
    }

    // ── Unsubmit Assignment ───────────────────────────────────────────────────

    public function unsubmitAssignment(Assignment $assignment)
    {
        $student = Auth::user();

        $submission = Submission::where('student_id', $student->id)
            ->where('assignment_id', $assignment->id)
            ->firstOrFail();

        if ($submission->status === 'graded') {
            return back()->with('error', 'Cannot unsubmit a graded assignment.');
        }

        // Only allow unsubmitting before the deadline
        if (Carbon::now()->isAfter($assignment->due_date)) {
            return back()->with('error', 'Cannot unsubmit after the deadline has passed.');
        }

        $submission->status = 'unsubmitted';
        $submission->save();

        // Log unsubmitted action to history
        SubmissionHistory::create([
            'submission_id' => $submission->id,
            'file_url'      => $submission->file_url,
            'code_content'  => $submission->code_content,
            'action'        => 'unsubmitted',
            'action_at'     => Carbon::now(),
        ]);

        AuditLogger::log('Assignment Unsubmitted', "Student {$student->name} unsubmitted {$assignment->title}.", $student->id);

        return back()->with('success', 'Assignment unsubmitted. You can now make changes and resubmit.');
    }

    // ── Grades & Feedback ─────────────────────────────────────────────────────

    public function grades()
    {
        $student = Auth::user();

        // Get active class enrollments
        $enrollments = ClassEnrollment::with(['academicClass.subject.teacher', 'academicClass.assignments.submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->get();

        $gradesData = [];

        foreach ($enrollments as $enrollment) {
            $class = $enrollment->academicClass;
            $subject = $class->subject;
            $assignments = $class->assignments()->where('is_published', true)->get();

            $totalGradedPoints = 0;
            $totalPossiblePoints = 0;
            $gradedCount = 0;
            $assignmentList = [];

            foreach ($assignments as $assignment) {
                $submission = $assignment->submissions->where('student_id', $student->id)->first();
                
                $points = null;
                $feedback = null;
                $status = 'pending';

                if ($submission) {
                    $status = $submission->status;
                    if ($status === 'graded') {
                        $points = $submission->points_earned;
                        $feedback = $submission->feedback;
                        $totalGradedPoints += $points;
                        $totalPossiblePoints += $assignment->max_points;
                        $gradedCount++;
                    }
                }

                $assignmentList[] = [
                    'title' => $assignment->title,
                    'max_points' => $assignment->max_points,
                    'due_date' => $assignment->due_date,
                    'status' => $status,
                    'points_earned' => $points,
                    'feedback' => $feedback,
                ];
            }

            $overallPercentage = $totalPossiblePoints > 0 ? ($totalGradedPoints / $totalPossiblePoints) * 100 : null;

            $gradesData[] = [
                'subject_name' => $subject->subject_name,
                'subject_code' => $subject->subject_code,
                'teacher_name' => $subject->teacher->name ?? 'Instructor',
                'class_section' => $class->section,
                'assignments' => $assignmentList,
                'overall_percentage' => $overallPercentage,
                'graded_count' => $gradedCount,
            ];
        }

        return view('student.grades', compact('gradesData'));
    }

    // ── Calendar ──────────────────────────────────────────────────────────────

    public function calendar()
    {
        $student = Auth::user();

        // Get enrolled class IDs
        $classIds = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_id');

        // Get all published assignments
        $assignments = Assignment::with('academicClass.subject')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->get();

        // Format events for FullCalendar
        $events = [];
        foreach ($assignments as $assignment) {
            $events[] = [
                'id' => $assignment->id,
                'title' => $assignment->title . ' (' . $assignment->academicClass->subject->subject_code . ')',
                'start' => $assignment->due_date->toIso8601String(),
                'url' => route('student.assignments.detail', $assignment->id),
                'color' => '#800000', // Maroon theme color
                'extendedProps' => [
                    'description' => $assignment->description,
                    'due_date' => $assignment->due_date->format('M d, Y g:i A'),
                    'subject' => $assignment->academicClass->subject->subject_name,
                ]
            ];
        }

        // Check Google Calendar Sync Status
        $syncedCount = CalendarIntegration::where('student_id', $student->id)->count();
        $isGoogleConnected = $student->calendar_notifications;

        return view('student.calendar', compact('events', 'syncedCount', 'isGoogleConnected'));
    }

    public function syncCalendar(Request $request)
    {
        $student = Auth::user();

        $classIds = ClassEnrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_id');

        $assignments = Assignment::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->get();

        $syncedCount = 0;
        foreach ($assignments as $assignment) {
            // Simulated google_event_id
            $mockEventId = 'gcal_evt_' . md5($student->id . '_' . $assignment->id . '_' . time());
            
            CalendarIntegration::updateOrCreate(
                ['student_id' => $student->id, 'assignment_id' => $assignment->id],
                ['google_event_id' => $mockEventId]
            );
            $syncedCount++;
        }

        AuditLogger::log('Google Calendar Synced', "Student {$student->name} synced {$syncedCount} deadlines to Google Calendar.", $student->id);

        return back()->with('success', "Google Calendar Synced Successfully! Synced {$syncedCount} deadlines to your Google Calendar.");
    }

    // ── Profile / Settings ────────────────────────────────────────────────────

    public function profile()
    {
        $student = Auth::user()->load('department');
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::user();

        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255|unique:users,email,' . $student->id,
            'email_notifications' => 'nullable|boolean',
            'calendar_notifications' => 'nullable|boolean',
        ]);

        $data['email_notifications'] = $request->has('email_notifications');
        $data['calendar_notifications'] = $request->has('calendar_notifications');

        $student->update($data);

        AuditLogger::log('Profile Updated', "Student {$student->name} updated their profile settings.", $student->id);

        return redirect()->route('student.profile')->with('success', 'Profile & Settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $student = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $student->update(['password' => Hash::make($request->password)]);

        AuditLogger::log('Password Changed', "Student {$student->name} changed their password.", $student->id);

        return redirect()->route('student.profile')->with('success', 'Password changed successfully.');
    }
}
