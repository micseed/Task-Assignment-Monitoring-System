<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\ClassEnrollment;
use App\Models\Department;
use App\Models\Subject;
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
        // 1. Seed Users (without department_id initially to resolve circular dependencies)
        
        // Admin / Dean
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

        // Additional default test admin
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

        // Teachers
        $teacherJose = User::updateOrCreate(
            ['email' => 'jose.reyes@school.edu'],
            [
                'first_name' => 'Jose',
                'last_name'  => 'Reyes',
                'email'      => 'jose.reyes@school.edu',
                'password'   => Hash::make('password'),
                'role'       => 'teacher',
            ]
        );

        $teacherAna = User::updateOrCreate(
            ['email' => 'ana.cruz@school.edu'],
            [
                'first_name' => 'Ana',
                'last_name'  => 'Cruz',
                'email'      => 'ana.cruz@school.edu',
                'password'   => Hash::make('password'),
                'role'       => 'teacher',
            ]
        );

        // Additional default test teacher
        User::updateOrCreate(
            ['email' => 'teacher@tam.test'],
            [
                'first_name' => 'Demo',
                'last_name'  => 'Teacher',
                'email'      => 'teacher@tam.test',
                'password'   => Hash::make('password'),
                'role'       => 'teacher',
            ]
        );

        // Students
        $studentJuan = User::updateOrCreate(
            ['email' => 'juan@student.edu'],
            [
                'first_name' => 'Juan',
                'last_name'  => 'Dela Cruz',
                'email'      => 'juan@student.edu',
                'password'   => Hash::make('password'),
                'role'       => 'student',
            ]
        );

        $studentLea = User::updateOrCreate(
            ['email' => 'lea@student.edu'],
            [
                'first_name' => 'Lea',
                'last_name'  => 'Gomez',
                'email'      => 'lea@student.edu',
                'password'   => Hash::make('password'),
                'role'       => 'student',
            ]
        );

        $studentMarco = User::updateOrCreate(
            ['email' => 'marco@student.edu'],
            [
                'first_name' => 'Marco',
                'last_name'  => 'Bautista',
                'email'      => 'marco@student.edu',
                'password'   => Hash::make('password'),
                'role'       => 'student',
            ]
        );

        // Additional default test student
        User::updateOrCreate(
            ['email' => 'student@tam.test'],
            [
                'first_name' => 'Demo',
                'last_name'  => 'Student',
                'email'      => 'student@tam.test',
                'password'   => Hash::make('password'),
                'role'       => 'student',
            ]
        );


        // 2. Seed Departments (linked to Maria Santos as Dean)
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


        // 3. Update Users with department_ids
        $teacherJose->update(['department_id' => $deptIT->id]);
        $studentJuan->update(['department_id' => $deptIT->id]);
        $studentLea->update(['department_id'  => $deptIT->id]);

        $teacherAna->update(['department_id' => $deptCS->id]);
        $studentMarco->update(['department_id' => $deptCS->id]);


        // 4. Seed Subjects
        $subjectWebDev = Subject::updateOrCreate(
            ['subject_code' => 'IT301'],
            [
                'subject_code'  => 'IT301',
                'subject_name'  => 'Web Development',
                'department_id' => $deptIT->id,
                'teacher_id'    => $teacherJose->id,
                'school_year'   => '2024-2025',
                'semester'      => '2nd',
            ]
        );

        $subjectDataStruct = Subject::updateOrCreate(
            ['subject_code' => 'CS201'],
            [
                'subject_code'  => 'CS201',
                'subject_name'  => 'Data Structures',
                'department_id' => $deptCS->id,
                'teacher_id'    => $teacherAna->id,
                'school_year'   => '2024-2025',
                'semester'      => '2nd',
            ]
        );


        // 5. Seed Classes
        $classIT3A = AcademicClass::updateOrCreate(
            [
                'subject_id' => $subjectWebDev->id,
                'section'    => 'BSIT 3-A',
            ],
            [
                'subject_id'  => $subjectWebDev->id,
                'section'     => 'BSIT 3-A',
                'school_year' => '2024-2025',
                'semester'    => '2nd',
            ]
        );

        $classCS2B = AcademicClass::updateOrCreate(
            [
                'subject_id' => $subjectDataStruct->id,
                'section'    => 'BSCS 2-B',
            ],
            [
                'subject_id'  => $subjectDataStruct->id,
                'section'     => 'BSCS 2-B',
                'school_year' => '2024-2025',
                'semester'    => '2nd',
            ]
        );


        // 6. Seed Enrollments
        ClassEnrollment::updateOrCreate(
            [
                'class_id'   => $classIT3A->id,
                'student_id' => $studentJuan->id,
            ],
            [
                'class_id'   => $classIT3A->id,
                'student_id' => $studentJuan->id,
                'status'     => 'active',
            ]
        );

        ClassEnrollment::updateOrCreate(
            [
                'class_id'   => $classIT3A->id,
                'student_id' => $studentLea->id,
            ],
            [
                'class_id'   => $classIT3A->id,
                'student_id' => $studentLea->id,
                'status'     => 'active',
            ]
        );

        ClassEnrollment::updateOrCreate(
            [
                'class_id'   => $classCS2B->id,
                'student_id' => $studentMarco->id,
            ],
            [
                'class_id'   => $classCS2B->id,
                'student_id' => $studentMarco->id,
                'status'     => 'active',
            ]
        );


        // 7. Seed Assignments
        Assignment::updateOrCreate(
            [
                'class_id' => $classIT3A->id,
                'title'    => 'HTML & CSS Portfolio',
            ],
            [
                'class_id'     => $classIT3A->id,
                'teacher_id'   => $teacherJose->id,
                'title'        => 'HTML & CSS Portfolio',
                'description'  => 'Build a personal portfolio site using HTML5 and CSS3. Submit as a ZIP file converted to PDF.',
                'type'         => 'pdf_upload',
                'max_points'   => 100.00,
                'due_date'     => '2025-03-15 23:59:00',
                'is_published' => true,
            ]
        );

        Assignment::updateOrCreate(
            [
                'class_id' => $classIT3A->id,
                'title'    => 'JavaScript Calculator',
            ],
            [
                'class_id'     => $classIT3A->id,
                'teacher_id'   => $teacherJose->id,
                'title'        => 'JavaScript Calculator',
                'description'  => 'Implement a functional calculator using vanilla JavaScript. Submit your .js source file.',
                'type'         => 'code_submission',
                'max_points'   => 50.00,
                'due_date'     => '2025-03-22 23:59:00',
                'is_published' => true,
            ]
        );

        Assignment::updateOrCreate(
            [
                'class_id' => $classCS2B->id,
                'title'    => 'Binary Search Tree Implementation',
            ],
            [
                'class_id'     => $classCS2B->id,
                'teacher_id'   => $teacherAna->id,
                'title'        => 'Binary Search Tree Implementation',
                'description'  => 'Implement insert, delete, and traversal methods for a BST in Java.',
                'type'         => 'code_submission',
                'max_points'   => 100.00,
                'due_date'     => '2025-03-20 23:59:00',
                'is_published' => true,
            ]
        );
    }
}
