@extends('layouts.admin_layout')

@section('title', 'Overview — WMSU TAMS Admin')
@section('page_title', 'Overview')
@section('admin_content')

    {{-- Welcome Banner --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <h1 class="text-xl font-bold text-gray-800 tracking-tight">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-sm text-gray-500 mt-1">
            You are administering the <strong class="text-gray-700 font-semibold">WMSU Task Assignment &amp; Monitoring System</strong>. Use the shortcuts and tools below to manage your catalog, review logs, and monitor performance.
        </p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Students --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-4
                    hover:-translate-y-1 hover:shadow-md transition-all duration-200 group">
            <div class="w-14 h-14 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800 tracking-tight leading-none">{{ $totalStudents }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-1">Total Students</p>
            </div>
        </div>

        {{-- Total Teachers --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-4
                    hover:-translate-y-1 hover:shadow-md transition-all duration-200 group">
            <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800 tracking-tight leading-none">{{ $totalTeachers }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-1">Total Teachers</p>
            </div>
        </div>

        {{-- Active Classes --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-4
                    hover:-translate-y-1 hover:shadow-md transition-all duration-200 group">
            <div class="w-14 h-14 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800 tracking-tight leading-none">{{ $activeClasses }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-1">Active Classes</p>
            </div>
        </div>

        {{-- Submission Rate --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-4
                    hover:-translate-y-1 hover:shadow-md transition-all duration-200 group">
            <div class="w-14 h-14 rounded-xl bg-maroon-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800 tracking-tight leading-none">{{ $submissionRate }}%</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-1">Submission Rate</p>
            </div>
        </div>
    </div>

    {{-- Main two-column layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-[1.8fr_1.2fr] gap-6">

        {{-- Left Column (Institution Oversight & System Status) --}}
        <div class="space-y-6">

            {{-- Institution Oversight Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-maroon-800 to-maroon-900 text-white p-6">
                <div class="absolute -bottom-8 -right-8 w-36 h-36 rounded-full bg-gold-500/10 blur-2xl"></div>
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_rgba(252,174,5,0.08)_0%,_transparent_60%)]"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gold-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <h3 class="text-gold-400 font-bold text-sm uppercase tracking-widest">Institution Oversight</h3>
                    </div>
                    <p class="text-white/70 text-sm leading-relaxed mb-5">
                        Ensure system integrity by monitoring deactivations, reviewing audit logs, and verifying all classes
                        have assigned teachers.
                    </p>
                    <p class="text-4xl font-extrabold tracking-tight">{{ \App\Models\Subject::whereNull('teacher_id')->count() }}</p>
                    <p class="text-gold-400 text-xs font-bold uppercase tracking-widest mt-1">Subjects Without Assigned Teachers</p>
                </div>
            </div>

            {{-- System Status Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">System Status Diagnostics</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Check Metric</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Value / Status</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Evaluation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-gray-600">Active School Year</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ \App\Models\Setting::get('school_year', '2024-2025') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Active Year
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-gray-600">Grading Scheme</td>
                                <td class="px-6 py-4 text-gray-700 capitalize">{{ \App\Models\Setting::get('grading_scheme', 'percentage') }} (passing: {{ \App\Models\Setting::get('passing_score', '75') }}%)</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>Evaluation Ready
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-gray-600">User Deactivations</td>
                                <td class="px-6 py-4 text-gray-700">{{ \App\Models\User::where('is_active', false)->count() }} accounts</td>
                                <td class="px-6 py-4">
                                    @if(\App\Models\User::where('is_active', false)->count() > 0)
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>Review Needed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Clean Audit
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-gray-600">Maintenance Mode</td>
                                <td class="px-6 py-4 font-medium {{ \App\Models\Setting::get('maintenance_mode', '0') === '1' ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ \App\Models\Setting::get('maintenance_mode', '0') === '1' ? 'ENABLED' : 'DISABLED' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(\App\Models\Setting::get('maintenance_mode', '0') === '1')
                                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>Maintenance On
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Online
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column (Shortcuts & Recent System Activity) --}}
        <div class="space-y-6">

            {{-- Quick Actions Card (Shortcuts) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Academic Shortcuts</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-500 leading-relaxed mb-5">
                        Use the system management shortcuts below for common task monitoring and administrative operations.
                    </p>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('admin.users') }}"
                           class="flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            Manage User Accounts
                        </a>
                        <a href="{{ route('admin.classes') }}"
                           class="flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                            Class &amp; Catalog Setup
                        </a>
                        <a href="{{ route('admin.reports') }}"
                           class="flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            View Performance Reports
                        </a>
                        <a href="{{ route('admin.settings') }}"
                           class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Portal System Settings
                        </a>
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Recent System Activity</h2>
                    <a href="{{ route('admin.audit_logs') }}" class="text-xs text-maroon-700 font-semibold hover:underline">View all</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentActivity as $log)
                        <div class="px-6 py-4 flex items-start gap-3">
                            <span class="mt-1.5 w-2 h-2 rounded-full bg-gold-500 flex-shrink-0"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $log->action }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $log->description }}</p>
                                <div class="flex items-center justify-between mt-1.5 gap-2">
                                    <span class="flex items-center gap-1 text-[11px] text-gray-400">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        {{ $log->user ? $log->user->name : 'System' }}
                                    </span>
                                    <span class="flex items-center gap-1 text-[11px] text-gray-400">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                            <p class="text-sm text-gray-400">No recent activity logged.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
