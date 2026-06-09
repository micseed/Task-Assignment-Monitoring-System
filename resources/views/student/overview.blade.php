@extends('layouts.student_layout')

@section('title', 'Student Dashboard')
@section('page_header', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                <i class="fa-solid fa-list-check text-xl"></i>
            </div>
            <div>
                <div class="text-sm text-gray-500 font-medium">Total Assignments</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalAssignments }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600 mr-4">
                <i class="fa-solid fa-check-double text-xl"></i>
            </div>
            <div>
                <div class="text-sm text-gray-500 font-medium">Submitted</div>
                <div class="text-2xl font-bold text-gray-800">{{ $submittedCount }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 mr-4">
                <i class="fa-regular fa-clock text-xl"></i>
            </div>
            <div>
                <div class="text-sm text-gray-500 font-medium">Pending Tasks</div>
                <div class="text-2xl font-bold text-gray-800">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fa-regular fa-calendar-days text-maroon-600 mr-2"></i> Upcoming Deadlines
                </h2>
                <a href="{{ route('student.assignments') }}" class="text-sm text-maroon-600 hover:text-maroon-800 font-medium">View All</a>
            </div>
            <div class="p-0 flex-1">
                @if($upcomingDeadlines->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        <i class="fa-solid fa-check-circle text-4xl text-green-200 mb-3"></i>
                        <p>No upcoming deadlines. You're all caught up!</p>
                    </div>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach($upcomingDeadlines as $task)
                            <li class="p-5 hover:bg-gray-50 transition-colors">
                                <a href="{{ route('student.assignments.detail', $task->id) }}" class="flex justify-between items-start">
                                    <div class="pr-4">
                                        <div class="font-semibold text-gray-800 hover:text-maroon-700 transition-colors">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-500 mt-1">{{ $task->academicClass->subject->subject_name }} - {{ $task->academicClass->section }}</div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-sm font-medium {{ Carbon\Carbon::parse($task->due_date)->diffInDays(now()) <= 2 ? 'text-red-600' : 'text-gray-700' }}">
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d, g:i A') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}</div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Overdue & Graded -->
        <div class="space-y-6">
            <!-- Overdue Tasks -->
            @if($overdueTasks->isNotEmpty())
            <div class="bg-red-50/50 rounded-xl shadow-sm border border-red-100 flex flex-col">
                <div class="p-5 border-b border-red-100">
                    <h2 class="text-lg font-semibold text-red-700">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i> Overdue Tasks
                    </h2>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-red-100">
                        @foreach($overdueTasks as $task)
                            <li class="p-4 hover:bg-red-50 transition-colors">
                                <a href="{{ route('student.assignments.detail', $task->id) }}" class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-red-800">{{ $task->title }}</div>
                                        <div class="text-xs text-red-600 mt-1">Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</div>
                                    </div>
                                    @if($task->allow_late)
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-md font-medium">Late turn-in allowed</span>
                                    @else
                                        <span class="text-xs px-2 py-1 bg-gray-200 text-gray-600 rounded-md font-medium">Closed</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Recently Graded -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fa-solid fa-star text-yellow-500 mr-2"></i> Recently Graded
                    </h2>
                </div>
                <div class="p-0">
                    @if($recentlyGraded->isEmpty())
                        <div class="p-6 text-center text-gray-500 text-sm">
                            No graded assignments yet.
                        </div>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach($recentlyGraded as $submission)
                                <li class="p-4 flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-800">{{ $submission->assignment->title }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Graded {{ \Carbon\Carbon::parse($submission->graded_at)->diffForHumans() }}</div>
                                    </div>
                                    <div class="bg-green-50 text-green-700 px-3 py-1.5 rounded-lg font-bold text-sm border border-green-100">
                                        {{ $submission->points_earned }} / {{ $submission->assignment->max_points }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
