<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Seed standard accounts
        User::updateOrCreate(
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
    }
}
