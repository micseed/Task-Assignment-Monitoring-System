<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Root redirect ─────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Guest routes ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// ── Authenticated routes ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/welcome', fn () => view('welcome'))->name('welcome');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ── Admin Dashboard routes ─────────────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
        Route::post('/users/{user}/update', [AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');

        // Class & Subject Management
        Route::get('/classes', [AdminController::class, 'classes'])->name('classes');
        Route::post('/classes/store', [AdminController::class, 'storeClass'])->name('classes.store');
        Route::post('/classes/enroll', [AdminController::class, 'enrollStudent'])->name('classes.enroll');
        Route::post('/classes/unenroll/{enrollment}', [AdminController::class, 'unenrollStudent'])->name('classes.unenroll');
        Route::post('/subjects/store', [AdminController::class, 'storeSubject'])->name('subjects.store');
        Route::post('/subjects/{subject}/assign', [AdminController::class, 'assignTeacher'])->name('subjects.assign');

        // Reports, Logs & Settings
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit_logs');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');
    });

    // ── Teacher Dashboard routes ───────────────────────────────────────────
    Route::middleware('teacher')->prefix('teacher')->name('teacher.')->group(function () {
        // Overview
        Route::get('/dashboard', [TeacherController::class, 'overview'])->name('dashboard');

        // My Classes
        Route::get('/classes', [TeacherController::class, 'myClasses'])->name('classes');
        Route::get('/classes/{class}', [TeacherController::class, 'classDetail'])->name('classes.detail');

        // Assignment Manager
        Route::get('/assignments', [TeacherController::class, 'assignments'])->name('assignments');
        Route::post('/assignments/store', [TeacherController::class, 'storeAssignment'])->name('assignments.store');
        Route::post('/assignments/{assignment}/update', [TeacherController::class, 'updateAssignment'])->name('assignments.update');
        Route::delete('/assignments/{assignment}/delete', [TeacherController::class, 'deleteAssignment'])->name('assignments.delete');
        Route::post('/assignments/{assignment}/publish', [TeacherController::class, 'togglePublish'])->name('assignments.publish');

        // Submissions Inbox
        Route::get('/submissions', [TeacherController::class, 'submissionsInbox'])->name('submissions.inbox');
        Route::get('/submissions/{submission}', [TeacherController::class, 'submissionDetail'])->name('submissions.detail');
        Route::post('/submissions/{submission}/grade', [TeacherController::class, 'gradeSubmission'])->name('submissions.grade');

        // Reminders
        Route::get('/reminders', [TeacherController::class, 'reminders'])->name('reminders');
        Route::post('/reminders/send', [TeacherController::class, 'sendReminder'])->name('reminders.send');

        // Profile
        Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [TeacherController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [TeacherController::class, 'updatePassword'])->name('profile.password');
    });

    // ── Student Dashboard routes ───────────────────────────────────────────
    Route::middleware('student')->prefix('student')->name('student.')->group(function () {
        // Overview
        Route::get('/dashboard', [StudentController::class, 'overview'])->name('dashboard');

        // My Assignments
        Route::get('/assignments', [StudentController::class, 'assignments'])->name('assignments');
        Route::get('/assignments/{assignment}', [StudentController::class, 'assignmentDetail'])->name('assignments.detail');
        Route::post('/assignments/{assignment}/submit', [StudentController::class, 'submitAssignment'])->name('assignments.submit');
        Route::post('/assignments/{assignment}/unsubmit', [StudentController::class, 'unsubmitAssignment'])->name('assignments.unsubmit');

        // Grades & Feedback
        Route::get('/grades', [StudentController::class, 'grades'])->name('grades');

        // Calendar & Google Calendar integration
        Route::get('/calendar', [StudentController::class, 'calendar'])->name('calendar');
        Route::post('/calendar/sync', [StudentController::class, 'syncCalendar'])->name('calendar.sync');

        // Profile
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [StudentController::class, 'updatePassword'])->name('profile.password');
    });
});
