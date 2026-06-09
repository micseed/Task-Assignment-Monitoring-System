@extends('layouts.teacher_layout')

@section('title', 'Submission Detail — WMSU TAMS Teacher')
@section('page_title', 'Submission Detail')

@section('teacher_content')

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('teacher.submissions.inbox') }}" class="hover:text-maroon-700 font-medium transition-colors">Submissions Inbox</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-semibold">{{ $submission->student->name }}</span>
    </nav>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main content --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Assignment Info --}}
            <div class="bg-gradient-to-r from-maroon-900 to-maroon-700 rounded-2xl p-5 text-white">
                <p class="text-maroon-300 text-xs font-bold uppercase tracking-widest mb-1">
                    {{ $submission->assignment->academicClass->subject->subject_code }}
                    · {{ $submission->assignment->academicClass->section }}
                </p>
                <h2 class="text-lg font-extrabold tracking-tight">{{ $submission->assignment->title }}</h2>
                <div class="flex flex-wrap gap-4 mt-3 text-sm text-maroon-200">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Due: {{ $submission->assignment->due_date->format('M d, Y g:i A') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                        Max: {{ $submission->assignment->max_points }} pts
                    </span>
                </div>
            </div>

            {{-- Student Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-600
                                text-white font-bold text-base flex items-center justify-center uppercase">
                        {{ substr($submission->student->first_name, 0, 1) }}{{ substr($submission->student->last_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $submission->student->name }}</p>
                        <p class="text-sm text-gray-400">{{ $submission->student->email }}</p>
                    </div>
                    <div class="ml-auto flex flex-col items-end gap-1.5">
                        @if($submission->status === 'graded')
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>Graded
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>Pending Grade
                            </span>
                        @endif
                        @if($submission->is_late)
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">Late Submission</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Submitted</p>
                        <p class="font-medium">{{ $submission->submitted_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $submission->submitted_at->format('g:i A') }}</p>
                    </div>
                    @if($submission->status === 'graded')
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Graded</p>
                            <p class="font-medium">{{ $submission->graded_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $submission->graded_at->format('g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Score</p>
                            <p class="text-xl font-extrabold text-emerald-600">
                                {{ $submission->points_earned }}<span class="text-sm text-gray-400 font-normal">/{{ $submission->assignment->max_points }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Submission Content --}}
            @if($submission->file_url)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Submitted File</h3>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ basename($submission->file_url) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Student uploaded file</p>
                        </div>
                        <a href="/storage/{{ $submission->file_url }}" target="_blank"
                           class="flex items-center gap-1.5 text-xs font-bold text-maroon-700 bg-maroon-50 px-3 py-2 rounded-lg
                                  hover:bg-maroon-100 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            @endif

            @if($submission->code_content)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-900">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            </div>
                            <span class="text-xs font-mono text-gray-400 ml-2">
                                {{ $submission->code_language ?? 'plaintext' }}
                            </span>
                        </div>
                    </div>
                    <pre class="text-xs font-mono text-gray-800 bg-gray-950 p-5 overflow-x-auto leading-relaxed max-h-80"><code>{{ $submission->code_content }}</code></pre>
                </div>
            @endif

            @if(!$submission->file_url && !$submission->code_content)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-sm text-gray-400">
                    No file or code content in this submission.
                </div>
            @endif

            {{-- Submission history --}}
            @if($submission->histories->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Submission History</h3>
                    <ol class="relative border-l border-gray-200 space-y-4 ml-3">
                        @foreach($submission->histories as $h)
                            <li class="ml-4">
                                <div class="absolute w-2.5 h-2.5 bg-maroon-700 rounded-full -left-1.5 border-2 border-white"></div>
                                <p class="text-xs text-gray-400">{{ $h->created_at->format('M d, Y g:i A') }}</p>
                                <p class="text-sm text-gray-700 font-medium mt-0.5">{{ $h->action ?? 'Resubmitted' }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>

        {{-- Sidebar: Grading Form --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-maroon-800 to-maroon-700 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-white uppercase tracking-wide">Grade Submission</h3>
                </div>
                <form method="POST" action="{{ route('teacher.submissions.grade', $submission->id) }}" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Points Earned <span class="text-gray-400 font-normal normal-case">(max {{ $submission->assignment->max_points }})</span>
                        </label>
                        <input type="number" name="points_earned" step="0.01"
                               min="0" max="{{ $submission->assignment->max_points }}"
                               value="{{ $submission->points_earned ?? '' }}"
                               placeholder="0"
                               class="w-full text-lg font-bold border border-gray-200 rounded-xl px-4 py-3
                                      focus:outline-none focus:ring-2 focus:ring-maroon-700/20 text-center tracking-wider">
                    </div>

                    {{-- Grade percentage preview --}}
                    <div id="grade-preview" class="text-center text-sm text-gray-400 py-1">
                        @if($submission->points_earned !== null)
                            <span class="text-lg font-extrabold text-emerald-600">
                                {{ round(($submission->points_earned / $submission->assignment->max_points) * 100, 1) }}%
                            </span>
                        @else
                            Enter a score to see percentage
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Feedback / Comments
                        </label>
                        <textarea name="feedback" rows="5" placeholder="Write your feedback here..."
                                  class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3
                                         focus:outline-none focus:ring-2 focus:ring-maroon-700/20 resize-none">{{ $submission->feedback }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-gradient-to-r from-maroon-800 to-maroon-700 text-white text-sm font-bold
                                   rounded-xl hover:brightness-110 transition-all">
                        {{ $submission->status === 'graded' ? 'Update Grade' : 'Submit Grade' }}
                    </button>
                </form>

                @if($submission->status === 'graded' && $submission->feedback)
                    <div class="px-5 pb-5">
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3">
                            <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">Current Feedback</p>
                            <p class="text-sm text-gray-700">{{ $submission->feedback }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const pointsInput = document.querySelector('input[name="points_earned"]');
    const maxPoints   = {{ $submission->assignment->max_points }};
    const preview     = document.getElementById('grade-preview');

    pointsInput && pointsInput.addEventListener('input', function () {
        const val = parseFloat(this.value);
        if (!isNaN(val) && val >= 0 && val <= maxPoints) {
            const pct = Math.round((val / maxPoints) * 1000) / 10;
            const color = pct >= 75 ? 'text-emerald-600' : pct >= 50 ? 'text-amber-500' : 'text-red-500';
            preview.innerHTML = `<span class="text-lg font-extrabold ${color}">${pct}%</span>`;
        } else {
            preview.innerHTML = `<span class="text-gray-400 text-sm">Enter a valid score</span>`;
        }
    });
</script>
@endpush
