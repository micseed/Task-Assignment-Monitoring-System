@extends('layouts.teacher_layout')

@section('title', 'Class Detail — WMSU TAMS Teacher')
@section('page_title', 'Class Roster')

@section('teacher_content')

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('teacher.classes') }}" class="hover:text-maroon-700 font-medium transition-colors">My Classes</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-semibold">{{ $class->section }}</span>
    </nav>

    {{-- Class Header --}}
    <div class="bg-gradient-to-r from-maroon-900 to-maroon-700 rounded-2xl p-6 mb-6 text-white">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-maroon-300 text-xs font-bold uppercase tracking-widest mb-1">{{ $class->subject->subject_code }}</p>
                <h2 class="text-xl font-extrabold tracking-tight">{{ $class->subject->subject_name }}</h2>
                <p class="text-maroon-200 text-sm mt-1">Section {{ $class->section }} &nbsp;·&nbsp; {{ $class->school_year }} &nbsp;·&nbsp; {{ $class->semester }} Semester</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white/15 rounded-xl px-4 py-3 text-center">
                    <p class="text-2xl font-extrabold">{{ $students->count() }}</p>
                    <p class="text-xs text-maroon-200 font-medium">Students</p>
                </div>
                <div class="bg-white/15 rounded-xl px-4 py-3 text-center">
                    <p class="text-2xl font-extrabold">{{ $assignments->count() }}</p>
                    <p class="text-xs text-maroon-200 font-medium">Assignments</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Assignment Filter (tabs) --}}
    @if($assignments->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Student Roster &amp; Submission Status</h3>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500 font-semibold">Filter by Assignment:</label>
                    <select id="assignment-filter" onchange="filterByAssignment(this.value)"
                            class="text-sm border border-gray-200 rounded-xl px-3 py-1.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-maroon-700/30 bg-white">
                        <option value="all">All Assignments</option>
                        @foreach($assignments as $asgn)
                            <option value="{{ $asgn->id }}">{{ $asgn->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="roster-table">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-6 py-3.5">Student</th>
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Email</th>
                            @foreach($assignments as $asgn)
                                <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-3 py-3.5 assignment-col"
                                    data-assignment="{{ $asgn->id }}" title="{{ $asgn->title }}">
                                    <div class="max-w-[90px] truncate mx-auto">{{ Str::limit($asgn->title, 12) }}</div>
                                    <div class="text-[10px] text-gray-400 font-normal normal-case">{{ $asgn->due_date->format('M d') }}</div>
                                </th>
                            @endforeach
                            <th class="text-right text-xs font-bold uppercase tracking-wider text-gray-500 px-6 py-3.5">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-600
                                                    text-white text-xs font-bold flex items-center justify-center uppercase flex-shrink-0">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">{{ $student->email }}</td>

                                @php $submitted = 0; @endphp
                                @foreach($assignments as $asgn)
                                    @php $key = $student->id . '_' . $asgn->id; $sub = $submissions->get($key); @endphp
                                    <td class="px-3 py-3.5 text-center assignment-col" data-assignment="{{ $asgn->id }}">
                                        @if(!$sub)
                                            <span title="Not submitted" class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-50">
                                                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                </svg>
                                            </span>
                                        @elseif($sub->status === 'graded')
                                            @php $submitted++; @endphp
                                            <a href="{{ route('teacher.submissions.detail', $sub->id) }}"
                                               title="Graded: {{ $sub->points_earned }}/{{ $asgn->max_points }}"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 hover:bg-emerald-100 transition-colors">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </a>
                                        @else
                                            @php $submitted++; @endphp
                                            <a href="{{ route('teacher.submissions.detail', $sub->id) }}"
                                               title="Submitted — pending grading"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-50 hover:bg-amber-100 transition-colors">
                                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-6 py-3.5 text-right">
                                    @php $rate = $assignments->count() > 0 ? round(($submitted / $assignments->count()) * 100) : 0; @endphp
                                    <span class="text-xs font-bold {{ $rate >= 75 ? 'text-emerald-600' : ($rate >= 50 ? 'text-amber-500' : 'text-red-500') }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 3 + $assignments->count() }}" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No students enrolled in this class yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Legend --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center gap-5 text-xs text-gray-500">
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </span>
                    Graded
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-amber-50 flex items-center justify-center">
                        <svg class="w-3 h-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                    Submitted (awaiting grade)
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-3 h-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </span>
                    Not submitted
                </div>
                <div class="ml-auto">
                    <a href="{{ route('teacher.reminders') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-maroon-700 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        Send Reminder to Non-Submitters
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-8 py-14 text-center">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            <p class="text-sm font-semibold text-gray-500 mb-2">No published assignments for this class.</p>
            <a href="{{ route('teacher.assignments') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-maroon-700 hover:underline">
                Create an assignment →
            </a>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    function filterByAssignment(val) {
        document.querySelectorAll('.assignment-col').forEach(el => {
            el.style.display = (val === 'all' || el.dataset.assignment === val) ? '' : 'none';
        });
    }
</script>
@endpush
