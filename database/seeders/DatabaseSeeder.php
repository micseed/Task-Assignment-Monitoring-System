<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin Users
        $adminDean = User::updateOrCreate(
            ['email' => 'admin@school.edu'],
            [
                'first_name' => 'Maria',
                'last_name'  => 'Santos',
                'email'      => 'admin@school.edu',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@tam.test'],
            [
                'first_name' => 'System',
                'last_name'  => 'Admin',
                'email'      => 'admin@tam.test',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
            ]
        );

        // 2. Seed Default Departments (mapped to Maria Santos as Dean)
        $deptIT = Department::updateOrCreate(
            ['code' => 'IT'],
            [
                'name'    => 'Information Technology',
                'code'    => 'IT',
                'dean_id' => $adminDean->id,
            ]
        );

        $deptCS = Department::updateOrCreate(
            ['code' => 'CS'],
            [
                'name'    => 'Computer Science',
                'code'    => 'CS',
                'dean_id' => $adminDean->id,
            ]
        );

        $deptIS = Department::updateOrCreate(
            ['code' => 'IS'],
            [
                'name'    => 'Information Systems',
                'code'    => 'IS',
                'dean_id' => $adminDean->id,
            ]
        );

        // 3. Seed Default Test Student
        $student = User::updateOrCreate(
            ['email' => 'student@tam.test'],
            [
                'first_name' => 'Demo',
                'last_name'  => 'Student',
                'email'      => 'student@tam.test',
                'password'   => Hash::make('password'),
                'role'       => 'student',
                'department_id' => $deptIT->id,
            ]
        );

        // 4. Seed Teacher
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@tam.test'],
            [
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'teacher@tam.test',
                'password'   => Hash::make('password'),
                'role'       => 'teacher',
                'department_id' => $deptIT->id,
            ]
        );

        // 5. Seed Subject
        $subject = \App\Models\Subject::updateOrCreate(
            ['subject_code' => 'IT-312'],
            [
                'subject_name'  => 'Advanced Web Development',
                'subject_code'  => 'IT-312',
                'department_id' => $deptIT->id,
                'teacher_id'    => $teacher->id,
                'school_year'   => '2025-2026',
                'semester'      => '2nd',
            ]
        );

        // 6. Seed Academic Class
        $class = \App\Models\AcademicClass::updateOrCreate(
            ['subject_id' => $subject->id, 'section' => 'BSIT-3A'],
            [
                'subject_id'  => $subject->id,
                'section'     => 'BSIT-3A',
                'school_year' => '2025-2026',
                'semester'    => '2nd',
            ]
        );

        // 7. Enroll Student in Class
        \App\Models\ClassEnrollment::updateOrCreate(
            ['class_id' => $class->id, 'student_id' => $student->id],
            [
                'class_id'   => $class->id,
                'student_id' => $student->id,
                'status'     => 'active',
            ]
        );

        // 8. Seed 4 Test Assignments & Submissions
        // Assignment 1: PDF Document Submission (Pending, due in 3 days)
        $assign1 = \App\Models\Assignment::updateOrCreate(
            ['class_id' => $class->id, 'title' => 'PDF Document Submission'],
            [
                'teacher_id'   => $teacher->id,
                'description'  => 'Read chapters 1-3 and upload your review summary as a PDF. Make sure your name is on the cover page.',
                'type'         => 'pdf_upload',
                'max_points'   => 100.00,
                'due_date'     => \Carbon\Carbon::now()->addDays(3),
                'allow_late'   => false,
                'is_published' => true,
            ]
        );

        // Assignment 2: Programming Exercise 1 (Graded, due 5 days ago)
        $assign2 = \App\Models\Assignment::updateOrCreate(
            ['class_id' => $class->id, 'title' => 'Programming Exercise 1'],
            [
                'teacher_id'   => $teacher->id,
                'description'  => 'Write a PHP function that sorts a multi-dimensional array by key. Submit code below.',
                'type'         => 'code_submission',
                'max_points'   => 100.00,
                'due_date'     => \Carbon\Carbon::now()->subDays(5),
                'allow_late'   => true,
                'is_published' => true,
            ]
        );

        $sub2 = \App\Models\Submission::updateOrCreate(
            ['assignment_id' => $assign2->id, 'student_id' => $student->id],
            [
                'code_content'  => "<?php\nfunction sortByKey(&\$arr, \$key) {\n    usort(\$arr, fn(\$a, \$b) => \$a[\$key] <=> \$b[\$key]);\n}",
                'code_language' => 'php',
                'status'        => 'graded',
                'submitted_at'  => \Carbon\Carbon::now()->subDays(6),
                'is_late'       => false,
                'points_earned' => 90.00,
                'feedback'      => 'Great code logic! Nicely commented.',
                'graded_at'     => \Carbon\Carbon::now()->subDays(4),
                'graded_by'     => $teacher->id,
            ]
        );

        \App\Models\SubmissionHistory::updateOrCreate(
            ['submission_id' => $sub2->id, 'action' => 'submitted'],
            [
                'code_content' => $sub2->code_content,
                'action_at'    => \Carbon\Carbon::now()->subDays(6),
            ]
        );

        \App\Models\SubmissionHistory::updateOrCreate(
            ['submission_id' => $sub2->id, 'action' => 'resubmitted'],
            [
                'code_content' => $sub2->code_content,
                'action_at'    => \Carbon\Carbon::now()->subDays(5.5),
            ]
        );

        // Assignment 3: Midterm Project Design (Submitted, due in 10 days)
        $assign3 = \App\Models\Assignment::updateOrCreate(
            ['class_id' => $class->id, 'title' => 'Midterm Project Design'],
            [
                'teacher_id'   => $teacher->id,
                'description'  => 'Upload both the design diagram (PDF) and the database schema script (SQL/Code).',
                'type'         => 'both',
                'max_points'   => 100.00,
                'due_date'     => \Carbon\Carbon::now()->addDays(10),
                'allow_late'   => true,
                'is_published' => true,
            ]
        );

        $sub3 = \App\Models\Submission::updateOrCreate(
            ['assignment_id' => $assign3->id, 'student_id' => $student->id],
            [
                'file_url'     => 'submissions/mock_file.pdf',
                'code_content' => "-- Midterm DB SQL Script\nCREATE TABLE students (\n    id INT AUTO_INCREMENT PRIMARY KEY,\n    name VARCHAR(255)\n);",
                'code_language'=> 'sql',
                'status'       => 'submitted',
                'submitted_at' => \Carbon\Carbon::now()->subDay(),
                'is_late'      => false,
            ]
        );

        \App\Models\SubmissionHistory::updateOrCreate(
            ['submission_id' => $sub3->id, 'action' => 'submitted'],
            [
                'file_url'     => $sub3->file_url,
                'code_content' => $sub3->code_content,
                'action_at'    => \Carbon\Carbon::now()->subDay(),
            ]
        );

        // Assignment 4: Late Closed Assignment (Overdue, due 2 days ago)
        $assign4 = \App\Models\Assignment::updateOrCreate(
            ['class_id' => $class->id, 'title' => 'Late Closed Assignment'],
            [
                'teacher_id'   => $teacher->id,
                'description'  => 'Submit your research summary. Late turn-ins are disabled.',
                'type'         => 'pdf_upload',
                'max_points'   => 50.00,
                'due_date'     => \Carbon\Carbon::now()->subDays(2),
                'allow_late'   => false,
                'is_published' => true,
            ]
        );
    }
}

