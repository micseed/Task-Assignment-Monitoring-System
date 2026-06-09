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

        {{-- Submission History --}}
        @if($submissionHistories->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800">
                        <i class="fa-solid fa-history mr-2 text-maroon-700"></i> Submission History
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($submissionHistories as $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @php
                                                    $bgIcon = match($history->action) {
                                                        'submitted' => 'bg-blue-500 text-white',
                                                        'unsubmitted' => 'bg-gray-400 text-white',
                                                        'resubmitted' => 'bg-indigo-500 text-white',
                                                        default => 'bg-gray-400 text-white'
                                                    };
                                                    $faIcon = match($history->action) {
                                                        'submitted' => 'fa-paper-plane',
                                                        'unsubmitted' => 'fa-undo',
                                                        'resubmitted' => 'fa-rotate-right',
                                                        default => 'fa-info'
                                                    };
                                                @endphp
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $bgIcon }}">
                                                    <i class="fa-solid {{ $faIcon }} text-[10px]"></i>
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-800 font-medium">
                                                        Work <span class="font-bold capitalize text-maroon-800">{{ $history->action }}</span>
                                                    </p>
                                                    
                                                    @if($history->file_url)
                                                        <div class="mt-2">
                                                            <a href="{{ asset('storage/' . $history->file_url) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-maroon-700 hover:text-maroon-900 bg-maroon-50 hover:bg-maroon-100 px-2.5 py-1 rounded transition-colors">
                                                                <i class="fa-solid fa-file-pdf"></i> View Submitted File
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($history->code_content)
                                                        <div class="mt-2">
                                                            <button type="button" onclick="toggleCodeBlock('code-hist-{{ $history->id }}')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-gray-900 bg-gray-150 hover:bg-gray-200 px-2.5 py-1 rounded transition-colors border border-gray-250">
                                                                <i class="fa-solid fa-code"></i> Toggle Submitted Code
                                                            </button>
                                                            <div id="code-hist-{{ $history->id }}" class="hidden mt-2 p-3 bg-gray-950 text-gray-250 rounded-lg text-xs font-mono whitespace-pre overflow-x-auto max-h-40 border border-gray-900">
                                                                {{ $history->code_content }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $history->action_at }}">{{ $history->action_at->format('M d, Y - g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Submission Area -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
            <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Your Work</h2>
                
                @php
                    $status = $submission ? $submission->status : 'pending';
                    $statusClass = match($status) {
                        'graded' => 'bg-emerald-100 text-emerald-800',
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'unsubmitted' => 'bg-gray-100 text-gray-800',
                        default => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                    };
                    $statusText = match($status) {
                        'graded' => 'Graded',
                        'submitted' => 'Submitted',
                        'unsubmitted' => 'Unsubmitted',
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
                    <div class="mb-6 p-4 bg-green-50 border border-green-250 rounded-lg text-center">
                        <div class="text-xs text-green-600 font-bold uppercase tracking-wider mb-1">Score</div>
                        <div class="text-4xl font-black text-green-700">{{ $submission->points_earned }} <span class="text-lg text-green-500 font-medium">/ {{ $assignment->max_points }}</span></div>
                    </div>
                    
                    @if($submission->feedback)
                    <div class="mb-4">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Teacher Feedback</div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 italic">
                            "{{ $submission->feedback }}"
                        </div>
                    </div>
                    @endif

                    @if($submission->file_url || $submission->code_content)
                        <div class="mb-4 space-y-2 pt-2 border-t border-gray-100">
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Your Submission</div>
                            @if($submission->file_url)
                                <a href="{{ asset('storage/' . $submission->file_url) }}" target="_blank" class="flex items-center gap-1.5 text-xs text-gray-700 hover:text-maroon-700">
                                    <i class="fa-solid fa-file-pdf text-red-500"></i> View PDF File
                                </a>
                            @endif
                            @if($submission->code_content)
                                <button type="button" onclick="toggleCodeBlock('code-graded-submission')" class="flex items-center gap-1.5 text-xs text-gray-700 hover:text-maroon-700">
                                    <i class="fa-solid fa-code"></i> View Code Answer
                                </button>
                                <div id="code-graded-submission" class="hidden mt-2 p-3 bg-gray-950 text-gray-250 rounded-lg text-xs font-mono whitespace-pre overflow-x-auto max-h-40 border border-gray-900">
                                    {{ $submission->code_content }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                        <i class="fa-solid fa-lock text-gray-300 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-500">This assignment has been graded and is locked.</p>
                    </div>

                <!-- If Submitted but not graded -->
                @elseif($status === 'submitted')
                    <div class="text-center mb-6">
                        <i class="fa-solid fa-circle-check text-4xl text-blue-500 mb-3 animate-[pulse_2s_infinite]"></i>
                        <p class="text-gray-700 font-semibold">Your work has been turned in</p>
                        <p class="text-xs text-gray-400 mt-1">Submitted: {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y g:i A') }}</p>
                    </div>

                    {{-- Submitted Files & Code Display --}}
                    <div class="mb-5 space-y-3 pt-3 border-t border-gray-100">
                        @if($submission->file_url)
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-700 truncate max-w-[170px]">
                                    <i class="fa-solid fa-file-pdf text-red-500 mr-1.5"></i> {{ basename($submission->file_url) }}
                                </span>
                                <a href="{{ asset('storage/' . $submission->file_url) }}" target="_blank" class="text-maroon-700 hover:text-maroon-900 font-bold">
                                    View File
                                </a>
                            </div>
                        @endif

                        @if($submission->code_content)
                            <div>
                                <button type="button" onclick="toggleCodeBlock('code-submission-current')" class="w-full flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-100">
                                    <span><i class="fa-solid fa-code text-gray-500 mr-1.5"></i> View Submitted Code</span>
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </button>
                                <div id="code-submission-current" class="hidden mt-2 p-3 bg-gray-950 text-gray-250 rounded-lg text-xs font-mono whitespace-pre overflow-x-auto max-h-48 border border-gray-900">
                                    {{ $submission->code_content }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(Carbon\Carbon::now()->isBefore($assignment->due_date))
                        <form action="{{ route('student.assignments.unsubmit', $assignment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unsubmit? You will need to turn it in again before the deadline.');">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all active:scale-[0.98]">
                                Unsubmit Work
                            </button>
                        </form>
                    @else
                        <div class="text-center p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-400">
                            Unsubmit is disabled because the deadline has passed.
                        </div>
                    @endif

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

@push('scripts')
<script>
    function toggleCodeBlock(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('hidden');
        }
    }
</script>
@endpush
@endsection
