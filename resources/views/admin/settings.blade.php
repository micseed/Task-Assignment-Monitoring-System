@extends('layouts.admin_layout')

@section('title', 'System Settings — WMSU TAMS Admin')

@section('admin_content')

    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Portal System Configurations</h2>
        <p class="text-sm text-gray-500 mt-1">Adjust term variables, grading limits, compliance alerts, notifications thresholds, and preferences.</p>
    </div>

    <div class="max-w-3xl space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            {{-- 1. Term & Academic Settings --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    <h3 class="text-sm font-bold text-maroon-700 uppercase tracking-wider">Term &amp; Academic Settings</h3>
                </div>
                <div class="divide-y divide-gray-50">

                    {{-- School Year --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Academic School Year</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Default term label for newly generated classes and evaluations.</p>
                        </div>
                        <div>
                            <select id="school_year" name="school_year"
                                    class="w-full max-w-[250px] px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="2024-2025" {{ $school_year === '2024-2025' ? 'selected' : '' }}>2024–2025</option>
                                <option value="2025-2026" {{ $school_year === '2025-2026' ? 'selected' : '' }}>2025–2026</option>
                                <option value="2026-2027" {{ $school_year === '2026-2027' ? 'selected' : '' }}>2026–2027</option>
                            </select>
                        </div>
                    </div>

                    {{-- Grading Scheme --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Grading Scheme Model</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Standard grading representation for evaluating class submissions.</p>
                        </div>
                        <div>
                            <select id="grading_scheme" name="grading_scheme"
                                    class="w-full max-w-[250px] px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="percentage" {{ $grading_scheme === 'percentage' ? 'selected' : '' }}>Numeric / Percentage (%)</option>
                                <option value="gpa" {{ $grading_scheme === 'gpa' ? 'selected' : '' }}>GPA Scale (1.0 – 5.0)</option>
                                <option value="standard" {{ $grading_scheme === 'standard' ? 'selected' : '' }}>Letter Grades (A – F)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Passing Score --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Passing Compliance Score (%)</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Points below this rate identify students as failing or at‑risk.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="range" id="passing_score_slider" min="50" max="95" step="1"
                                   value="{{ $passing_score }}"
                                   oninput="document.getElementById('passing_score_val').innerText = this.value; document.getElementById('passing_score_hidden').value = this.value"
                                   class="flex-1 accent-maroon-700" />
                            <span class="text-lg font-extrabold text-gray-800 w-14 text-right">
                                <span id="passing_score_val">{{ $passing_score }}</span>%
                            </span>
                            <input type="hidden" name="passing_score" id="passing_score_hidden" value="{{ $passing_score }}" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Notification Rules --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <h3 class="text-sm font-bold text-maroon-700 uppercase tracking-wider">Notification Rules &amp; Reminders</h3>
                </div>
                <div class="divide-y divide-gray-50">

                    {{-- Email Alerts --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Enable System Email Alerts</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Sends transaction emails for reminders, notifications, and task assignments.</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_notifications" value="1"
                                       class="sr-only peer" {{ $enable_notifications === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-maroon-700/25 rounded-full peer
                                            peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                                            after:content-[''] after:absolute after:top-[3px] after:start-[3px] after:bg-white after:border-gray-300
                                            after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all
                                            peer-checked:bg-maroon-700"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Auto-Reminder Days --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Auto-Reminder Frequency (Days)</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Days prior to task due dates to send auto reminder alerts to students.</p>
                        </div>
                        <div>
                            <select id="auto_reminder_days" name="auto_reminder_days"
                                    class="w-full max-w-[180px] px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                @for($i=1; $i<=7; $i++)
                                    <option value="{{ $i }}" {{ $auto_reminder_days == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ Str::plural('Day', $i) }} before
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Platform Administration --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <h3 class="text-sm font-bold text-maroon-700 uppercase tracking-wider">Platform &amp; System Administration</h3>
                </div>
                <div class="divide-y divide-gray-50">

                    {{-- Self Registration --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Student Self-Registration</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Allow self account creation for students from the authentication landing portal.</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="allow_self_registration" value="1"
                                       class="sr-only peer" {{ $allow_self_registration === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-maroon-700/25 rounded-full peer
                                            peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                                            after:content-[''] after:absolute after:top-[3px] after:start-[3px] after:bg-white after:border-gray-300
                                            after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all
                                            peer-checked:bg-maroon-700"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Maintenance Mode --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1.8fr] gap-4 px-6 py-5 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Oversight Maintenance Mode</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Enables offline maintenance banner, restricting system operations and locking teacher/student tasks.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" value="1"
                                       class="sr-only peer" {{ $maintenance_mode === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-400/25 rounded-full peer
                                            peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                                            after:content-[''] after:absolute after:top-[3px] after:start-[3px] after:bg-white after:border-gray-300
                                            after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all
                                            peer-checked:bg-red-500"></div>
                            </label>
                            @if($maintenance_mode === '1')
                                <span class="flex items-center gap-1 bg-red-50 text-red-600 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>Active
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pb-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" onclick="syncHiddenPassing()"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white
                               bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl shadow-sm hover:brightness-110 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function syncHiddenPassing() {
        document.getElementById('passing_score_hidden').value = document.getElementById('passing_score_slider').value;
    }
    document.getElementById('passing_score_slider').addEventListener('input', function () {
        document.getElementById('passing_score_hidden').value = this.value;
    });
</script>
@endpush
