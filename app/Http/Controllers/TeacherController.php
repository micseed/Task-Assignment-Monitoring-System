<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\AcademicClass;
use App\Models\ClassEnrollment;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Reminder;
use App\Helpers\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeacherController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Get all class IDs for the current teacher (via their subjects).
     */
    private function teacherClassIds(): \Illuminate\Support\Collection
    {
        $teacher = Auth::user();
        $subjectIds = Subject::where('teacher_id', $teacher->id)->pluck('id');
        return AcademicClass::whereIn('subject_id', $subjectIds)->pluck('id');
    }

    // ── Overview / Home ───────────────────────────────────────────────────────

    public function overview()
    {
        $teacher = Auth::user();
        $classIds = $this->teacherClassIds();

        $activeClasses = AcademicClass::whereIn('id', $classIds)->count();

        $publishedAssignments = Assignment::where('teacher_id', $teacher->id)
            ->where('is_published', true)
            ->count();

        $totalAssignments = Assignment::where('teacher_id', $teacher->id)->count();

        // Pending submissions = submitted status (not yet graded)
        $pendingGrading = Submission::whereHas('assignment', fn($q) =>
            $q->where('teacher_id', $teacher->id)
        )->where('status', 'submitted')->count();

        // Total submitted (all time)
        $totalSubmissions = Submission::whereHas('assignment', fn($q) =>
            $q->where('teacher_id', $teacher->id)
        )->whereIn('status', ['submitted', 'graded'])->count();

        // Recent submissions (last 8)
        $recentSubmissions = Submission::with(['student', 'assignment.academicClass'])
            ->whereHas('assignment', fn($q) =>
                $q->where('teacher_id', $teacher->id)
            )
            ->whereIn('status', ['submitted', 'graded'])
            ->orderBy('submitted_at', 'desc')
            ->limit(8)
            ->get();

        // Upcoming deadlines (due in next 14 days, published)
        $upcomingDeadlines = Assignment::with('academicClass.subject')
            ->where('teacher_id', $teacher->id)
            ->where('is_published', true)
            ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(14)])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Class summary: each class with student count and submission rate
        $classes = AcademicClass::with(['subject', 'students'])
            ->whereIn('id', $classIds)
            ->withCount(['students as enrolled_count' => function ($q) {
                $q->where('class_enrollments.status', 'active');
            }])
            ->get()
            ->map(function ($class) use ($teacher) {
                $expected = DB::table('assignments')
                    ->join('class_enrollments', 'assignments.class_id', '=', 'class_enrollments.class_id')
                    ->where('assignments.class_id', $class->id)
                    ->where('class_enrollments.status', 'active')
                    ->where('assignments.is_published', true)
                    ->count();

                $actual = Submission::whereHas('assignment', fn($q) =>
                    $q->where('class_id', $class->id)->where('teacher_id', $teacher->id)
                )->whereIn('status', ['submitted', 'graded'])->count();

                $class->submission_rate = $expected > 0 ? round(($actual / $expected) * 100, 1) : 0;
                $class->expected_submissions = $expected;
                $class->actual_submissions = $actual;

                return $class;
            });

        return view('teacher.overview', compact(
            'activeClasses',
            'publishedAssignments',
            'totalAssignments',
            'pendingGrading',
            'totalSubmissions',
            'recentSubmissions',
            'upcomingDeadlines',
            'classes'
        ));
    }

    // ── My Classes ────────────────────────────────────────────────────────────

    public function myClasses()
    {
        $teacher = Auth::user();
        $classIds = $this->teacherClassIds();

        $classes = AcademicClass::with(['subject.department'])
            ->whereIn('id', $classIds)
            ->withCount(['students as enrolled_count' => function ($q) {
                $q->where('class_enrollments.status', 'active');
            }])
            ->withCount('assignments')
            ->get()
            ->map(function ($class) use ($teacher) {
                $expected = DB::table('assignments')
                    ->join('class_enrollments', 'assignments.class_id', '=', 'class_enrollments.class_id')
                    ->where('assignments.class_id', $class->id)
                    ->where('class_enrollments.status', 'active')
                    ->where('assignments.is_published', true)
                    ->count();

                $actual = Submission::whereHas('assignment', fn($q) =>
                    $q->where('class_id', $class->id)->where('teacher_id', $teacher->id)
                )->whereIn('status', ['submitted', 'graded'])->count();

                $class->submission_rate = $expected > 0 ? round(($actual / $expected) * 100, 1) : 0;
                $class->pending_grading = Submission::whereHas('assignment', fn($q) =>
                    $q->where('class_id', $class->id)->where('teacher_id', $teacher->id)
                )->where('status', 'submitted')->count();

                return $class;
            });

        return view('teacher.my_classes', compact('classes'));
    }

    // ── Class Detail ──────────────────────────────────────────────────────────

    public function classDetail(AcademicClass $class)
    {
        $teacher = Auth::user();

        // Verify this class belongs to the teacher
        $subjectIds = Subject::where('teacher_id', $teacher->id)->pluck('id');
        if (!in_array($class->subject_id, $subjectIds->toArray())) {
            abort(403, 'Access denied. This class is not assigned to you.');
        }

        $class->load(['subject.department']);

        // All published assignments for this class
        $assignments = Assignment::where('class_id', $class->id)
            ->where('is_published', true)
            ->orderBy('due_date')
            ->get();

        // All enrolled students
        $students = $class->students()
            ->where('class_enrollments.status', 'active')
            ->orderBy('last_name')
            ->get();

        // Submission matrix: student_id → assignment_id → submission
        $submissions = Submission::whereHas('assignment', fn($q) =>
            $q->where('class_id', $class->id)
        )->whereIn('student_id', $students->pluck('id'))
        ->get()
        ->keyBy(fn($s) => $s->student_id . '_' . $s->assignment_id);

        return view('teacher.class_detail', compact('class', 'assignments', 'students', 'submissions'));
    }

    // ── Assignment Manager ────────────────────────────────────────────────────

    public function assignments(Request $request)
    {
        $teacher = Auth::user();
        $classIds = $this->teacherClassIds();

        $classes = AcademicClass::with('subject')
            ->whereIn('id', $classIds)
            ->get();

        $query = Assignment::with(['academicClass.subject'])
            ->where('teacher_id', $teacher->id);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $assignments = $query->withCount('submissions')->orderBy('due_date', 'desc')->get();

        return view('teacher.assignments', compact('assignments', 'classes'));
    }

    public function storeAssignment(Request $request)
    {
        $teacher = Auth::user();

        $data = $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:pdf_upload,code_submission,both',
            'max_points'  => 'required|numeric|min:1|max:1000',
            'due_date'    => 'required|date|after:now',
            'allow_late'  => 'boolean',
            'is_published'=> 'boolean',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,zip,txt|max:10240',
        ]);

        // Verify the class belongs to this teacher
        $class = AcademicClass::findOrFail($data['class_id']);
        $teacherSubjectIds = Subject::where('teacher_id', $teacher->id)->pluck('id');
        if (!in_array($class->subject_id, $teacherSubjectIds->toArray())) {
            return back()->with('error', 'You are not assigned to this class.');
        }

        $data['teacher_id'] = $teacher->id;
        $data['allow_late'] = $request->boolean('allow_late');
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('assignments', 'public');
            $data['description'] = ($data['description'] ?? '') . "\n[Attachment: /storage/{$path}]";
        }

        $assignment = Assignment::create($data);

        AuditLogger::log('Assignment Created', "Created assignment: {$assignment->title} for class {$class->section}", $teacher->id);

        return redirect()->route('teacher.assignments')->with('success', 'Assignment created successfully.');
    }

    public function updateAssignment(Request $request, Assignment $assignment)
    {
        $teacher = Auth::user();

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:pdf_upload,code_submission,both',
            'max_points'  => 'required|numeric|min:1|max:1000',
            'due_date'    => 'required|date',
            'allow_late'  => 'boolean',
        ]);

        $data['allow_late'] = $request->boolean('allow_late');
        $assignment->update($data);

        AuditLogger::log('Assignment Updated', "Updated assignment: {$assignment->title}", $teacher->id);

        return redirect()->route('teacher.assignments')->with('success', 'Assignment updated successfully.');
    }

    public function deleteAssignment(Assignment $assignment)
    {
        $teacher = Auth::user();

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        if ($assignment->submissions()->exists()) {
            return back()->with('error', 'Cannot delete: this assignment already has student submissions.');
        }

        $title = $assignment->title;
        $assignment->delete();

        AuditLogger::log('Assignment Deleted', "Deleted assignment: {$title}", $teacher->id);

        return redirect()->route('teacher.assignments')->with('success', 'Assignment deleted.');
    }

    public function togglePublish(Assignment $assignment)
    {
        $teacher = Auth::user();

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $assignment->is_published = !$assignment->is_published;
        $assignment->save();

        $status = $assignment->is_published ? 'published' : 'unpublished';

        AuditLogger::log('Assignment ' . ucfirst($status), "{$status}: {$assignment->title}", $teacher->id);

        return back()->with('success', "Assignment {$status} successfully.");
    }

    // ── Submissions Inbox ─────────────────────────────────────────────────────

    public function submissionsInbox(Request $request)
    {
        $teacher = Auth::user();
        $classIds = $this->teacherClassIds();

        $classes = AcademicClass::with('subject')->whereIn('id', $classIds)->get();
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->when($request->filled('class_id'), fn($q) => $q->where('class_id', $request->class_id))
            ->orderBy('title')
            ->get();

        $query = Submission::with(['student', 'assignment.academicClass.subject'])
            ->whereHas('assignment', fn($q) => $q->where('teacher_id', $teacher->id));

        if ($request->filled('class_id')) {
            $query->whereHas('assignment', fn($q) => $q->where('class_id', $request->class_id));
        }
        if ($request->filled('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderBy('submitted_at', 'desc')->paginate(20)->withQueryString();

        return view('teacher.submissions', compact('submissions', 'classes', 'assignments'));
    }

    public function submissionDetail(Submission $submission)
    {
        $teacher = Auth::user();

        // Ensure submission belongs to this teacher's assignment
        if ($submission->assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $submission->load(['student', 'assignment.academicClass.subject', 'histories']);

        return view('teacher.submission_detail', compact('submission'));
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        $teacher = Auth::user();

        if ($submission->assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $data = $request->validate([
            'points_earned' => 'required|numeric|min:0|max:' . $submission->assignment->max_points,
            'feedback'      => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'points_earned' => $data['points_earned'],
            'feedback'      => $data['feedback'] ?? null,
            'status'        => 'graded',
            'graded_at'     => Carbon::now(),
            'graded_by'     => $teacher->id,
        ]);

        AuditLogger::log('Submission Graded',
            "Graded {$submission->student->name}'s submission for \"{$submission->assignment->title}\"",
            $teacher->id
        );

        return redirect()->route('teacher.submissions.inbox')
            ->with('success', "Submission graded: {$data['points_earned']}/{$submission->assignment->max_points} pts.");
    }

    // ── Reminders & Notifications ─────────────────────────────────────────────

    public function reminders()
    {
        $teacher = Auth::user();
        $classIds = $this->teacherClassIds();

        $publishedAssignments = Assignment::with('academicClass.subject')
            ->where('teacher_id', $teacher->id)
            ->where('is_published', true)
            ->orderBy('due_date', 'desc')
            ->get();

        $reminderHistory = Reminder::with(['assignment.academicClass.subject', 'sender'])
            ->where('sent_by', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        return view('teacher.reminders', compact('publishedAssignments', 'reminderHistory'));
    }

    public function sendReminder(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'message'       => 'required|string|max:1000',
        ]);

        $assignment = Assignment::with(['academicClass.students', 'submissions'])->findOrFail($request->assignment_id);

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        // Find non-submitters
        $submittedStudentIds = $assignment->submissions()
            ->whereIn('status', ['submitted', 'graded'])
            ->pluck('student_id');

        $enrolledStudentIds = ClassEnrollment::where('class_id', $assignment->class_id)
            ->where('status', 'active')
            ->pluck('student_id');

        $nonSubmitters = $enrolledStudentIds->diff($submittedStudentIds);
        $recipientCount = $nonSubmitters->count();

        // Create reminder record
        Reminder::create([
            'assignment_id' => $assignment->id,
            'sent_by'       => $teacher->id,
            'message'       => $request->message,
            'target'        => 'non_submitters',
        ]);

        AuditLogger::log(
            'Reminder Sent',
            "Sent reminder to {$recipientCount} non-submitters for \"{$assignment->title}\"",
            $teacher->id
        );

        return redirect()->route('teacher.reminders')
            ->with('success', "Reminder sent to {$recipientCount} student(s) who haven't submitted yet.");
    }

    // ── Profile / Settings ────────────────────────────────────────────────────

    public function profile()
    {
        $teacher = Auth::user()->load('department');
        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = Auth::user();

        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255|unique:users,email,' . $teacher->id,
        ]);

        $teacher->update($data);

        AuditLogger::log('Profile Updated', "Teacher {$teacher->name} updated their profile.", $teacher->id);

        return redirect()->route('teacher.profile')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $teacher->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $teacher->update(['password' => Hash::make($request->password)]);

        AuditLogger::log('Password Changed', "Teacher {$teacher->name} changed their password.", $teacher->id);

        return redirect()->route('teacher.profile')->with('success', 'Password changed successfully.');
    }
}
