@extends('layouts.teacher_layout')

@section('title', 'Submissions Inbox — WMSU TAMS Teacher')
@section('page_title', 'Submissions Inbox')

@section('teacher_content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Submissions Inbox</h2>
            <p class="text-sm text-gray-500 mt-1">Review, grade, and give feedback on student submissions.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Class</label>
            <select name="class_id" onchange="this.form.submit()"
                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                <option value="">All Classes</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                        {{ $cls->section }} — {{ $cls->subject->subject_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Assignment</label>
            <select name="assignment_id"
                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                <option value="">All Assignments</option>
                @foreach($assignments as $asgn)
                    <option value="{{ $asgn->id }}" {{ request('assignment_id') == $asgn->id ? 'selected' : '' }}>
                        {{ $asgn->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status"
                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                <option value="">All</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Pending Grade</option>
                <option value="graded"    {{ request('status') === 'graded'    ? 'selected' : '' }}>Graded</option>
            </select>
        </div>
        <button type="submit"
                class="px-5 py-2 bg-gray-800 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 transition-colors">
            Apply
        </button>
        @if(request()->hasAny(['class_id','assignment_id','status']))
            <a href="{{ route('teacher.submissions.inbox') }}"
               class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                Clear
            </a>
        @endif
    </form>

    {{-- Submissions table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($submissions->isEmpty())
            <div class="px-8 py-16 text-center">
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                </svg>
                <p class="text-base font-semibold text-gray-400 mb-1">No submissions found</p>
                <p class="text-sm text-gray-400">Students haven't submitted anything yet, or try adjusting your filters.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-6 py-3.5">Student</th>
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Assignment</th>
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Class</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Submitted</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Late</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Grade</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Status</th>
                            <th class="text-right text-xs font-bold uppercase tracking-wider text-gray-500 px-6 py-3.5">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($submissions as $sub)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-600
                                                    text-white text-xs font-bold flex items-center justify-center uppercase flex-shrink-0">
                                            {{ substr($sub->student->first_name, 0, 1) }}{{ substr($sub->student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $sub->student->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $sub->student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-medium text-gray-700 line-clamp-1">{{ $sub->assignment->title }}</p>
                                    <p class="text-xs text-gray-400">Max: {{ $sub->assignment->max_points }} pts</p>
                                </td>
                                <td class="px-4 py-4 text-gray-500 text-xs">
                                    {{ $sub->assignment->academicClass->section }}
                                    <span class="text-gray-400">{{ $sub->assignment->academicClass->subject->subject_code }}</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <p class="text-xs font-medium text-gray-700">{{ $sub->submitted_at->format('M d, Y') }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $sub->submitted_at->format('g:i A') }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($sub->is_late)
                                        <span class="inline-block bg-red-50 text-red-600 text-[11px] font-bold px-2 py-0.5 rounded-full">Late</span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center font-bold text-sm">
                                    @if($sub->status === 'graded')
                                        <span class="text-emerald-600">{{ $sub->points_earned }}/{{ $sub->assignment->max_points }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($sub->status === 'graded')
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Graded
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('teacher.submissions.detail', $sub->id) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-maroon-800
                                              px-3 py-1.5 rounded-lg hover:bg-maroon-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($submissions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $submissions->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
