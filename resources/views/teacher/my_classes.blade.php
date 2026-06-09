@extends('layouts.teacher_layout')

@section('title', 'My Classes — WMSU TAMS Teacher')
@section('page_title', 'My Classes')

@section('teacher_content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">My Classes</h2>
            <p class="text-sm text-gray-500 mt-1">All subjects and sections assigned to you this term.</p>
        </div>
    </div>

    @if($classes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-8 py-16 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <p class="text-base font-semibold text-gray-500 mb-1">No classes assigned</p>
            <p class="text-sm text-gray-400">Contact the administrator to assign you to subjects and classes.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($classes as $class)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    {{-- Card header strip --}}
                    <div class="h-1.5 bg-gradient-to-r from-maroon-800 to-maroon-600"></div>

                    <div class="p-5">
                        {{-- Subject badge --}}
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-maroon-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-maroon-700 uppercase tracking-wider">{{ $class->subject->subject_code }}</p>
                                    <p class="text-sm font-bold text-gray-800 leading-tight">{{ $class->section }}</p>
                                </div>
                            </div>
                            @if($class->pending_grading > 0)
                                <span class="flex-shrink-0 bg-amber-400 text-white text-[11px] font-bold px-2.5 py-1 rounded-full">
                                    {{ $class->pending_grading }} to grade
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-gray-500 mb-4 line-clamp-1">{{ $class->subject->subject_name }}</p>

                        {{-- Meta info --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 rounded-xl px-3 py-2">
                                <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Students</p>
                                <p class="text-lg font-extrabold text-gray-800">{{ $class->enrolled_count }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-3 py-2">
                                <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Assignments</p>
                                <p class="text-lg font-extrabold text-gray-800">{{ $class->assignments_count }}</p>
                            </div>
                        </div>

                        {{-- Submission rate --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-semibold text-gray-500">Submission Rate</span>
                                <span class="text-xs font-bold text-maroon-700">{{ $class->submission_rate }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full bg-gradient-to-r from-maroon-800 to-maroon-600 transition-all"
                                     style="width: {{ $class->submission_rate }}%"></div>
                            </div>
                        </div>

                        {{-- Term info --}}
                        <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            {{ $class->school_year }} · {{ $class->semester }} Sem
                            @if($class->subject->department)
                                · {{ $class->subject->department->code }}
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ route('teacher.classes.detail', $class->id) }}"
                               class="flex-1 flex items-center justify-center gap-1.5 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700
                                      text-white text-xs font-bold rounded-xl hover:brightness-110 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                View Roster
                            </a>
                            <a href="{{ route('teacher.assignments') }}?class_id={{ $class->id }}"
                               class="flex items-center justify-center w-10 h-10 border border-gray-200 rounded-xl text-gray-500
                                      hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
