<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\ClassEnrollment;
use App\Models\Submission;
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

        // Upcoming Deadlines (not submitted yet)
        $submittedAssignmentIds = Submission::where('student_id', $student->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->pluck('assignment_id');

        $upcomingDeadlines = Assignment::with('academicClass.subject')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Overdue Tasks
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

        // Quick Stats
        $totalAssignments = Assignment::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->count();
        $submittedCount = $submittedAssignmentIds->count();
        $pendingCount = $totalAssignments - $submittedCount;

        return view('student.overview', compact(
            'upcomingDeadlines',
            'overdueTasks',
            'recentlyGraded',
            'totalAssignments',
            'submittedCount',
            'pendingCount'
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

        // Optional filtering by status can be implemented here based on the request

        $assignments = $query->orderBy('due_date', 'desc')->paginate(15);

        return view('student.assignments', compact('assignments'));
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

        return view('student.assignment_detail', compact('assignment', 'submission'));
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

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
            $submission->file_path = $path;
        }

        if ($request->filled('code_content')) {
            $submission->code_content = $data['code_content'];
        }

        $submission->status = 'submitted';
        $submission->submitted_at = Carbon::now();
        $submission->save();

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

        if (!$assignment->allow_late && Carbon::now()->isAfter($assignment->due_date)) {
            return back()->with('error', 'Cannot unsubmit after the deadline has passed.');
        }

        $submission->status = 'draft';
        $submission->save();

        AuditLogger::log('Assignment Unsubmitted', "Student {$student->name} unsubmitted {$assignment->title}.", $student->id);

        return back()->with('success', 'Assignment unsubmitted. You can now make changes and resubmit.');
    }

    // ── Profile ───────────────────────────────────────────────────────────────

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
        ]);

        $student->update($data);

        AuditLogger::log('Profile Updated', "Student {$student->name} updated their profile.", $student->id);

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully.');
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
