@extends('layouts.admin_layout')

@section('title', 'Department & Performance Reports — WMSU TAMS Admin')
@section('page_title', 'Department Reports')

@push('styles')
<style>
    @media print {
        body { background-color: #ffffff !important; color: #000 !important; }
        #sidebar, header, .no-print { display: none !important; }
        main { padding: 0 !important; margin: 0 !important; }
        .lg\:ml-72 { margin-left: 0 !important; }
    }
</style>
@endpush

@section('admin_content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Department &amp; Academic Analytics</h2>
            <p class="text-sm text-gray-500 mt-1">View analytics for student submission rates, grade distribution, and academic risk metrics.</p>
        </div>
        <button onclick="window.print()"
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700
                       text-white text-sm font-semibold rounded-xl shadow-sm hover:brightness-110 transition-all no-print">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>
            Export / Print Report
        </button>
    </div>

    {{-- Print Header --}}
    <div class="hidden print:block text-center border-b-2 border-gray-800 pb-4 mb-6">
        <h2 class="text-2xl font-extrabold uppercase">Western Mindanao State University</h2>
        <h3 class="text-lg font-bold text-gray-600 mt-1">Task Assignment &amp; Monitoring System (TAMS)</h3>
        <p class="text-sm mt-1">Dean Office — Academic Performance &amp; Compliance Audit Report</p>
        <p class="text-xs text-gray-500 mt-1">Date Generated: {{ date('F d, Y h:i A') }}</p>
    </div>

    {{-- SECTION 1: Submission Rate Analytics --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Department Submission Rate Analytics</h3>
            <span class="text-xs text-gray-400 no-print">Active Semester Performance</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider w-2/5">Department Name</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Expected</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Compliance Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($departmentsReport as $dept)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $dept['name'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $dept['code'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $dept['expected'] }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $dept['actual'] }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="font-bold text-maroon-700">{{ $dept['rate'] }}%</span>
                                    <span class="text-xs text-gray-400">{{ $dept['actual'] }}/{{ $dept['expected'] }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-maroon-800 to-maroon-600 transition-all"
                                         style="width: {{ $dept['rate'] }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: Grade Distribution --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-wrap gap-3">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Grade Distribution Analytics per Subject</h3>
            <div class="flex items-center gap-3 no-print text-xs font-semibold text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>Excellent (&ge;90%)</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500 inline-block"></span>Good (80–89%)</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>Passing (75–79%)</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>Failing (&lt;75%)</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Graded Tasks</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Avg / High / Low</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider w-2/5">Grade Distribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($subjectsReport as $subj)
                        @php $total = $subj['total_graded']; @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $subj['code'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $subj['name'] }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $total }} Graded
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($total > 0)
                                    <p class="font-bold text-gray-800">{{ $subj['avg_grade'] }}%</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Max: {{ $subj['max_grade'] }}% | Min: {{ $subj['min_grade'] }}%</p>
                                @else
                                    <span class="italic text-xs text-gray-400">No evaluations</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($total > 0)
                                    @php
                                        $exPct = ($subj['dist']['excellent'] / $total) * 100;
                                        $gdPct = ($subj['dist']['good'] / $total) * 100;
                                        $psPct = ($subj['dist']['passing'] / $total) * 100;
                                        $flPct = ($subj['dist']['failing'] / $total) * 100;
                                    @endphp
                                    <div class="flex h-3 rounded-full overflow-hidden bg-gray-100 w-full">
                                        <div class="bg-emerald-500 h-full" style="width:{{ $exPct }}%" title="Excellent: {{ $subj['dist']['excellent'] }} ({{ round($exPct) }}%)"></div>
                                        <div class="bg-sky-500 h-full" style="width:{{ $gdPct }}%" title="Good: {{ $subj['dist']['good'] }} ({{ round($gdPct) }}%)"></div>
                                        <div class="bg-amber-400 h-full" style="width:{{ $psPct }}%" title="Passing: {{ $subj['dist']['passing'] }} ({{ round($psPct) }}%)"></div>
                                        <div class="bg-red-500 h-full" style="width:{{ $flPct }}%" title="Failing: {{ $subj['dist']['failing'] }} ({{ round($flPct) }}%)"></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] text-gray-400 mt-1">
                                        <span>Exc: {{ $subj['dist']['excellent'] }}</span>
                                        <span>Good: {{ $subj['dist']['good'] }}</span>
                                        <span>Pass: {{ $subj['dist']['passing'] }}</span>
                                        <span class="text-red-600 font-semibold">Fail: {{ $subj['dist']['failing'] }}</span>
                                    </div>
                                @else
                                    <span class="italic text-xs text-gray-400">Waiting for graded task submissions.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-sm text-gray-400">No subject records.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 3: At-Risk Students --}}
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-red-100 bg-red-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                <h3 class="text-sm font-bold text-red-700 uppercase tracking-wide">Identify At-Risk Students</h3>
            </div>
            <span class="text-xs text-red-400 no-print">Students with &lt;75% submission rate or failing averages</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Submission Rate</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Avg Grade</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Risk Factor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($atRiskStudents as $risk)
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $risk['student']->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $risk['student']->email }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-red-600">{{ $risk['submission_rate'] }}%</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $risk['submitted'] }}/{{ $risk['expected'] }})</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($risk['avg_grade_pct'] !== null)
                                    <span class="font-semibold {{ $risk['avg_grade_pct'] < 75 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $risk['avg_grade_pct'] }}%
                                    </span>
                                @else
                                    <span class="italic text-xs text-gray-400">No grades</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    {{ $risk['reason'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <svg class="w-12 h-12 text-emerald-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <p class="text-sm font-semibold text-emerald-600">Good standing: No students identified as academically at-risk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
