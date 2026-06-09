@extends('layouts.student_layout')

@section('title', $assignment->title)
@section('page_header', 'Assignment Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Details & Instructions -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $assignment->title }}</h1>
                        <p class="text-gray-500 mt-1">{{ $assignment->academicClass->subject->subject_name }} ({{ $assignment->academicClass->section }})</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Points</div>
                        <div class="text-xl font-bold text-maroon-700">{{ $assignment->max_points }}</div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-4 text-sm mt-4 pb-4 border-b border-gray-100">
                    <div class="flex items-center text-gray-600">
                        <i class="fa-solid fa-user-tie w-5 text-gray-400"></i>
                        {{ $assignment->teacher->name ?? 'Instructor' }}
                    </div>
                    <div class="flex items-center {{ Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                        <i class="fa-regular fa-calendar-xmark w-5 {{ Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('D, M d, Y - g:i A') }}
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fa-solid fa-file-code w-5 text-gray-400"></i>
                        Type: <span class="capitalize ml-1">{{ str_replace('_', ' ', $assignment->type) }}</span>
                    </div>
                </div>

                <div class="mt-6 prose max-w-none text-gray-800">
                    <h3 class="text-lg font-semibold mb-2">Instructions</h3>
                    <div class="whitespace-pre-wrap bg-gray-50 p-4 rounded-lg border border-gray-100">{{ $assignment->description }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Submission Area -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
            <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Your Work</h2>
                
                @php
                    $status = $submission ? $submission->status : 'pending';
                    $statusClass = match($status) {
                        'graded' => 'bg-green-100 text-green-800',
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'draft' => 'bg-gray-100 text-gray-800',
                        default => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                    };
                    $statusText = match($status) {
                        'graded' => 'Graded',
                        'submitted' => 'Submitted',
                        'draft' => 'Draft',
                        default => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'Overdue' : 'Assigned'
                    };
                @endphp
                
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusClass }}">
                    {{ $statusText }}
                </span>
            </div>
            
            <div class="p-6">
                <!-- If Graded -->
                @if($status === 'graded')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                        <div class="text-sm text-green-600 font-bold uppercase tracking-wider mb-1">Score</div>
                        <div class="text-4xl font-black text-green-700">{{ $submission->points_earned }} <span class="text-xl text-green-500 font-medium">/ {{ $assignment->max_points }}</span></div>
                    </div>
                    
                    @if($submission->feedback)
                    <div class="mb-4">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Teacher Feedback</div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 italic">
                            "{{ $submission->feedback }}"
                        </div>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                        <i class="fa-solid fa-lock text-gray-300 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-500">This assignment has been graded and is locked.</p>
                    </div>

                <!-- If Submitted but not graded -->
                @elseif($status === 'submitted')
                    <div class="text-center mb-6">
                        <i class="fa-solid fa-cloud-check text-4xl text-blue-400 mb-3"></i>
                        <p class="text-gray-600 font-medium">Your work has been turned in.</p>
                        <p class="text-xs text-gray-400 mt-1">Submitted: {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y g:i A') }}</p>
                    </div>

                    <form action="{{ route('student.assignments.unsubmit', $assignment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unsubmit? You will need to turn it in again before the deadline.');">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 bg-white border-2 border-gray-300 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Unsubmit Work
                        </button>
                    </form>

                <!-- If Pending / Draft / Overdue -->
                @else
                    @if(!$assignment->allow_late && Carbon\Carbon::parse($assignment->due_date)->isPast())
                        <div class="text-center py-6">
                            <i class="fa-solid fa-calendar-xmark text-4xl text-red-400 mb-3"></i>
                            <p class="text-red-600 font-medium">Deadline Passed</p>
                            <p class="text-sm text-gray-500 mt-2">This assignment is closed and does not accept late submissions.</p>
                        </div>
                    @else
                        @if(Carbon\Carbon::parse($assignment->due_date)->isPast() && $assignment->allow_late)
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg flex items-start">
                                <i class="fa-solid fa-circle-info mt-0.5 mr-2"></i>
                                <span>You are submitting late. Your teacher may deduct points.</span>
                            </div>
                        @endif

                        <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            @if(in_array($assignment->type, ['pdf_upload', 'both']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">File Upload (PDF/ZIP)</label>
                                    <input type="file" name="file" accept=".pdf,.zip" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-maroon-50 file:text-maroon-700 hover:file:bg-maroon-100 border border-gray-300 rounded-md focus:ring-maroon-500 focus:border-maroon-500">
                                    @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            @if(in_array($assignment->type, ['code_submission', 'both']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Code / Text Answer</label>
                                    <textarea name="code_content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring focus:ring-maroon-200 focus:ring-opacity-50 font-mono text-sm" placeholder="Paste your code or answer here..."></textarea>
                                    @error('code_content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-maroon-700 hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon-500 transition-colors">
                                Turn In Assignment
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
