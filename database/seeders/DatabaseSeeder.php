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
    }
}
