<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Subject;
use App\Models\AcademicClass;
use App\Models\ClassEnrollment;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Helpers\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ── Overview / Home ──────────────────────────────────────────────────────
    public function dashboard()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $activeClasses = AcademicClass::count();

        // Overall submission rate = (submitted + graded submissions) / (expected submissions)
        // Expected submissions = sum of (students enrolled in class * assignments in that class) for published assignments
        $totalExpected = DB::table('assignments')
            ->join('class_enrollments', 'assignments.class_id', '=', 'class_enrollments.class_id')
            ->where('class_enrollments.status', 'active')
            ->where('assignments.is_published', true)
            ->count();

        $totalSubmitted = Submission::whereIn('status', ['submitted', 'graded'])->count();

        $submissionRate = $totalExpected > 0 ? round(($totalSubmitted / $totalExpected) * 100, 1) : 0;

        $recentActivity = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalTeachers', 
            'activeClasses', 
            'submissionRate', 
            'recentActivity'
        ));
    }

    // ── User Management ──────────────────────────────────────────────────────
    public function users(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $departmentId = $request->input('department_id');
        $status = $request->input('status');

        $query = User::with('department');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if (!empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === 'active');
        }

        $users = $query->orderBy('last_name')->orderBy('first_name')->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.users', compact('users', 'departments'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6',
            'role'          => 'required|in:student,teacher,admin',
            'department_id' => 'nullable|exists:departments,id',
            'is_active'     => 'boolean',
        ]);

        $data['password_hash'] = Hash::make($data['password']);
        unset($data['password']);
        $data['is_active'] = $request->has('is_active');

        $user = User::create($data);

        AuditLogger::log('User Created', "Created user accounts: {$user->name} ({$user->role})", Auth::id());

        return redirect()->route('admin.users')->with('success', 'User account created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:6',
            'role'          => 'required|in:student,teacher,admin',
            'department_id' => 'nullable|exists:departments,id',
            'is_active'     => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
        }
        unset($data['password']);

        $data['is_active'] = $request->has('is_active');

        $user->update($data);

        AuditLogger::log('User Updated', "Updated user details for: {$user->name}", Auth::id());

        return redirect()->route('admin.users')->with('success', 'User account updated successfully.');
    }

    public function toggleUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'activated' : 'deactivated';
        AuditLogger::log('User Status Toggled', "Account {$statusStr} for: {$user->name}", Auth::id());

        return redirect()->route('admin.users')->with('success', "User account has been {$statusStr}.");
    }

    // ── Class & Subject Management ───────────────────────────────────────────
    public function classes()
    {
        $classes = AcademicClass::with(['subject.teacher', 'subject.department', 'students'])
            ->withCount(['students' => function($q) {
                $q->where('class_enrollments.status', 'active');
            }])
            ->orderBy('section')
            ->get();

        $subjects = Subject::with(['teacher', 'department'])->orderBy('subject_name')->get();
        $teachers = User::where('role', 'teacher')->where('is_active', true)->orderBy('last_name')->get();
        $departments = Department::orderBy('name')->get();
        $students = User::where('role', 'student')->where('is_active', true)->orderBy('last_name')->get();

        return view('admin.classes', compact('classes', 'subjects', 'teachers', 'departments', 'students'));
    }

    public function storeClass(Request $request)
    {
        $data = $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'section'     => 'required|string|max:50',
            'school_year' => 'required|string|max:20',
            'semester'    => 'required|in:1st,2nd,Summer',
        ]);

        $class = AcademicClass::create($data);

        AuditLogger::log('Class Created', "Created class: {$class->section} for subject ID {$class->subject_id}", Auth::id());

        return redirect()->route('admin.classes')->with('success', 'Class created successfully.');
    }

    public function enrollStudent(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $classId = $request->input('class_id');
        $studentId = $request->input('student_id');

        // Verify role
        $student = User::find($studentId);
        if (!$student || !$student->isStudent()) {
            return redirect()->route('admin.classes')->with('error', 'Selected user is not a student.');
        }

        // Check active enrollment
        $existing = ClassEnrollment::where('class_id', $classId)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) {
            if ($existing->status === 'active') {
                return redirect()->route('admin.classes')->with('error', 'Student is already enrolled in this class.');
            }
            $existing->status = 'active';
            $existing->save();
        } else {
            ClassEnrollment::create([
                'class_id'   => $classId,
                'student_id' => $studentId,
                'status'     => 'active',
            ]);
        }

        $class = AcademicClass::find($classId);
        AuditLogger::log('Student Enrolled', "Enrolled student {$student->name} in class {$class->section}", Auth::id());

        return redirect()->route('admin.classes')->with('success', 'Student enrolled successfully.');
    }

    public function unenrollStudent(ClassEnrollment $enrollment)
    {
        $class = AcademicClass::find($enrollment->class_id);
        $student = User::find($enrollment->student_id);

        $enrollment->delete();

        AuditLogger::log('Student Unenrolled', "Removed student {$student->name} from class {$class->section}", Auth::id());

        return redirect()->route('admin.classes')->with('success', 'Student removed from class.');
    }

    public function storeSubject(Request $request)
    {
        $data = $request->validate([
            'subject_code'  => 'required|string|max:30|unique:subjects,subject_code',
            'subject_name'  => 'required|string|max:200',
            'department_id' => 'required|exists:departments,id',
            'teacher_id'    => 'nullable|exists:users,id',
            'school_year'   => 'required|string|max:20',
            'semester'      => 'required|in:1st,2nd,Summer',
        ]);

        if (!empty($data['teacher_id'])) {
            $teacher = User::find($data['teacher_id']);
            if (!$teacher || !$teacher->isTeacher()) {
                return redirect()->route('admin.classes')->with('error', 'Assigned user is not a teacher.');
            }
        }

        $subject = Subject::create($data);

        AuditLogger::log('Subject Created', "Created subject: {$subject->subject_code} - {$subject->subject_name}", Auth::id());

        return redirect()->route('admin.classes')->with('success', 'Subject catalog entry added.');
    }

    public function assignTeacher(Request $request, Subject $subject)
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $teacherId = $request->input('teacher_id');

        if ($teacherId) {
            $teacher = User::find($teacherId);
            if (!$teacher || !$teacher->isTeacher()) {
                return redirect()->route('admin.classes')->with('error', 'Assigned user is not a teacher.');
            }
            $subject->teacher_id = $teacherId;
            $subject->save();
            AuditLogger::log('Teacher Assigned', "Assigned teacher {$teacher->name} to subject {$subject->subject_code}", Auth::id());
        } else {
            $subject->teacher_id = null;
            $subject->save();
            AuditLogger::log('Teacher Unassigned', "Removed teacher from subject {$subject->subject_code}", Auth::id());
        }

        return redirect()->route('admin.classes')->with('success', 'Subject teacher updated.');
    }

    // ── Department Reports ───────────────────────────────────────────────────
    public function reports()
    {
        // 1. Submission Rate Analytics per Department
        $departments = Department::orderBy('name')->get();
        $departmentsReport = [];

        foreach ($departments as $dept) {
            // Find all subjects in this department
            $subjectIds = Subject::where('department_id', $dept->id)->pluck('id');
            // Find all classes for these subjects
            $classIds = AcademicClass::whereIn('subject_id', $subjectIds)->pluck('id');
            
            // Expected submissions in these classes
            $expected = DB::table('assignments')
                ->join('class_enrollments', 'assignments.class_id', '=', 'class_enrollments.class_id')
                ->whereIn('assignments.class_id', $classIds)
                ->where('class_enrollments.status', 'active')
                ->where('assignments.is_published', true)
                ->count();

            // Actual submissions
            $actual = DB::table('submissions')
                ->join('assignments', 'submissions.assignment_id', '=', 'assignments.id')
                ->whereIn('assignments.class_id', $classIds)
                ->whereIn('submissions.status', ['submitted', 'graded'])
                ->count();

            $rate = $expected > 0 ? round(($actual / $expected) * 100, 1) : 100.0;

            $departmentsReport[] = [
                'name' => $dept->name,
                'code' => $dept->code,
                'expected' => $expected,
                'actual' => $actual,
                'rate' => $rate,
            ];
        }

        // 2. Grade distribution and stats per Subject
        $subjects = Subject::with('classes')->orderBy('subject_code')->get();
        $subjectsReport = [];

        foreach ($subjects as $subject) {
            $classIds = $subject->classes->pluck('id');

            $allSubmissions = Submission::join('assignments', 'submissions.assignment_id', '=', 'assignments.id')
                ->whereIn('assignments.class_id', $classIds)
                ->where('submissions.status', 'graded')
                ->get();

            $totalGraded = $allSubmissions->count();
            
            $grades = $allSubmissions->pluck('points_earned')->map(fn($val) => (float)$val);

            $average = $totalGraded > 0 ? round($grades->average(), 2) : 0;
            $max = $totalGraded > 0 ? $grades->max() : 0;
            $min = $totalGraded > 0 ? $grades->min() : 0;

            // Distribution
            $excellent = 0; // >= 90%
            $good = 0;      // 80% - 89.99%
            $passing = 0;   // 75% - 79.99%
            $failing = 0;   // < 75%

            foreach ($allSubmissions as $sub) {
                $maxPoints = (float)$sub->assignment->max_points;
                if ($maxPoints > 0) {
                    $pct = ((float)$sub->points_earned / $maxPoints) * 100;
                    if ($pct >= 90) $excellent++;
                    elseif ($pct >= 80) $good++;
                    elseif ($pct >= 75) $passing++;
                    else $failing++;
                }
            }

            $subjectsReport[] = [
                'code' => $subject->subject_code,
                'name' => $subject->subject_name,
                'total_graded' => $totalGraded,
                'avg_grade' => $average,
                'max_grade' => $max,
                'min_grade' => $min,
                'dist' => [
                    'excellent' => $excellent,
                    'good' => $good,
                    'passing' => $passing,
                    'failing' => $failing,
                ]
            ];
        }

        // 3. Identify At-Risk Students
        // A student is at risk if they have enrollments and their submission rate is < 75%
        $students = User::where('role', 'student')->where('is_active', true)->get();
        $atRiskStudents = [];

        foreach ($students as $student) {
            // Find all classes student is enrolled in
            $classIds = ClassEnrollment::where('student_id', $student->id)
                ->where('status', 'active')
                ->pluck('class_id');

            if ($classIds->isEmpty()) {
                continue; // No enrollments, not at risk
            }

            // Expected submissions (published assignments in enrolled classes)
            $expected = Assignment::whereIn('class_id', $classIds)
                ->where('is_published', true)
                ->count();

            if ($expected === 0) {
                continue; // No assignments published yet
            }

            // Actual submissions
            $actual = Submission::where('student_id', $student->id)
                ->whereIn('status', ['submitted', 'graded'])
                ->count();

            $rate = round(($actual / $expected) * 100, 1);

            // Fetch average grade for this student
            $avgGradePct = 0;
            $gradedCount = 0;
            $studentSubmissions = Submission::where('student_id', $student->id)
                ->where('status', 'graded')
                ->with('assignment')
                ->get();

            $totalGradedPctSum = 0;
            foreach ($studentSubmissions as $sub) {
                $maxPoints = (float)$sub->assignment->max_points;
                if ($maxPoints > 0) {
                    $totalGradedPctSum += ((float)$sub->points_earned / $maxPoints) * 100;
                    $gradedCount++;
                }
            }

            $avgGradePct = $gradedCount > 0 ? round($totalGradedPctSum / $gradedCount, 1) : null;

            // At-risk criteria: submission rate < 75% OR average grade < 75% (if graded)
            if ($rate < 75 || ($avgGradePct !== null && $avgGradePct < 75)) {
                $atRiskStudents[] = [
                    'student' => $student,
                    'submission_rate' => $rate,
                    'expected' => $expected,
                    'submitted' => $actual,
                    'avg_grade_pct' => $avgGradePct,
                    'reason' => ($rate < 75) 
                        ? 'Low Submission Rate (' . $rate . '%)' 
                        : 'Low Average Grade (' . $avgGradePct . '%)'
                ];
            }
        }

        return view('admin.reports', compact('departmentsReport', 'subjectsReport', 'atRiskStudents'));
    }

    // ── Audit Logs ───────────────────────────────────────────────────────────
    public function auditLogs(Request $request)
    {
        $search = $request->input('search');

        $query = AuditLog::with('user');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.audit_logs', compact('logs'));
    }

    // ── Settings ─────────────────────────────────────────────────────────────
    public function settings()
    {
        $school_year = Setting::get('school_year', '2024-2025');
        $grading_scheme = Setting::get('grading_scheme', 'percentage');
        $passing_score = Setting::get('passing_score', '75');
        $enable_notifications = Setting::get('enable_notifications', '1');
        $auto_reminder_days = Setting::get('auto_reminder_days', '3');
        $allow_self_registration = Setting::get('allow_self_registration', '0');
        $maintenance_mode = Setting::get('maintenance_mode', '0');

        return view('admin.settings', compact(
            'school_year',
            'grading_scheme',
            'passing_score',
            'enable_notifications',
            'auto_reminder_days',
            'allow_self_registration',
            'maintenance_mode'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'school_year'        => 'required|string|max:20',
            'grading_scheme'     => 'required|in:percentage,gpa,standard',
            'passing_score'      => 'required|integer|min:0|max:100',
            'auto_reminder_days' => 'required|integer|min:1|max:14',
        ]);

        Setting::set('school_year', $request->input('school_year'));
        Setting::set('grading_scheme', $request->input('grading_scheme'));
        Setting::set('passing_score', $request->input('passing_score'));
        Setting::set('auto_reminder_days', $request->input('auto_reminder_days'));
        
        Setting::set('enable_notifications', $request->has('enable_notifications') ? '1' : '0');
        Setting::set('allow_self_registration', $request->has('allow_self_registration') ? '1' : '0');
        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');

        AuditLogger::log('Settings Updated', 'Updated system-wide preference configurations.', Auth::id());

        return redirect()->route('admin.settings')->with('success', 'System settings updated successfully.');
    }
}
