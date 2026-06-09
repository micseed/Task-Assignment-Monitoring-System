@extends('layouts.teacher_layout')

@section('title', 'Overview — WMSU TAMS Teacher')
@section('page_title', 'Overview')

@section('teacher_content')

    {{-- Welcome Banner --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h1 class="text-xl font-bold text-gray-800 tracking-tight">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-sm text-gray-500 mt-1">Here's a snapshot of your classes and student activity.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Active Classes',        'value' => $activeClasses,         'color' => 'blue',    'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
                ['label' => 'Published Assignments', 'value' => $publishedAssignments,  'color' => 'emerald', 'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z'],
                ['label' => 'Pending Grading',       'value' => $pendingGrading,         'color' => 'amber',   'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['label' => 'Total Submissions',     'value' => $totalSubmissions,       'color' => 'maroon',  'icon' => 'M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z'],
            ];
            $colorMap = [
                'blue'    => ['bg' => 'bg-sky-50',     'icon' => 'text-sky-500',     'num' => 'text-sky-700'],
                'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-500', 'num' => 'text-emerald-700'],
                'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-500',   'num' => 'text-amber-700'],
                'maroon'  => ['bg' => 'bg-maroon-50',  'icon' => 'text-maroon-700',  'num' => 'text-maroon-700'],
            ];
        @endphp

        @foreach($stats as $stat)
            @php $c = $colorMap[$stat['color']]; @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4
                        hover:-translate-y-1 hover:shadow-md transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold {{ $c['num'] }}">{{ $stat['value'] }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $stat['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Class Summary --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- My Classes Quick View --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Class Summary</h3>
                    <a href="{{ route('teacher.classes') }}" class="text-xs text-maroon-700 font-semibold hover:underline">View All →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($classes as $class)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/50 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-maroon-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm">{{ $class->section }}</p>
                                <p class="text-xs text-gray-400">{{ $class->subject->subject_code }} — {{ $class->subject->subject_name }}</p>
                                <div class="mt-1.5 flex items-center gap-3">
                                    <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full bg-gradient-to-r from-maroon-800 to-maroon-600"
                                             style="width: {{ $class->submission_rate }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-maroon-700 whitespace-nowrap">{{ $class->submission_rate }}%</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-gray-700">{{ $class->enrolled_count }}</p>
                                <p class="text-[11px] text-gray-400">students</p>
                            </div>
                            <a href="{{ route('teacher.classes.detail', $class->id) }}"
                               class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400
                                      hover:text-maroon-700 hover:border-maroon-200 hover:bg-maroon-50 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <p class="text-sm text-gray-400 font-medium">No classes assigned yet. Contact your administrator.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Submissions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Recent Submissions</h3>
                    <a href="{{ route('teacher.submissions.inbox') }}" class="text-xs text-maroon-700 font-semibold hover:underline">View All →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentSubmissions as $sub)
                        <a href="{{ route('teacher.submissions.detail', $sub->id) }}"
                           class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition-colors block">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-600 text-white
                                        text-xs font-bold flex items-center justify-center flex-shrink-0 uppercase">
                                {{ substr($sub->student->first_name, 0, 1) }}{{ substr($sub->student->last_name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $sub->student->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $sub->assignment->title }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                @if($sub->status === 'graded')
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Graded
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>Pending
                                    </span>
                                @endif
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $sub->submitted_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-gray-400">No submissions yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right: Upcoming Deadlines + Quick Actions --}}
        <div class="space-y-5">

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('teacher.assignments') }}"
                       class="flex items-center gap-3 w-full px-4 py-3 bg-gradient-to-r from-maroon-800 to-maroon-700
                              text-white text-sm font-semibold rounded-xl hover:brightness-110 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create Assignment
                    </a>
                    <a href="{{ route('teacher.submissions.inbox') }}"
                       class="flex items-center gap-3 w-full px-4 py-3 border border-gray-200 bg-gray-50
                              text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                        </svg>
                        Review Submissions
                        @if($pendingGrading > 0)
                            <span class="ml-auto bg-amber-400 text-white text-[11px] font-bold px-2 py-0.5 rounded-full">
                                {{ $pendingGrading }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('teacher.reminders') }}"
                       class="flex items-center gap-3 w-full px-4 py-3 border border-gray-200 bg-gray-50
                              text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        Send Reminders
                    </a>
                </div>
            </div>

            {{-- Upcoming Deadlines --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Upcoming Deadlines</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Next 14 days</p>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($upcomingDeadlines as $asgn)
                        <div class="px-6 py-3.5">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $asgn->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $asgn->academicClass->section ?? '—' }}</p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    @php $daysLeft = now()->diffInDays($asgn->due_date, false); @endphp
                                    <span class="text-xs font-bold {{ $daysLeft <= 2 ? 'text-red-600' : 'text-maroon-700' }}">
                                        {{ $daysLeft === 0 ? 'Today' : ($daysLeft === 1 ? 'Tomorrow' : "In {$daysLeft}d") }}
                                    </span>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $asgn->due_date->format('M d, g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-400">
                            No upcoming deadlines in the next 14 days.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
