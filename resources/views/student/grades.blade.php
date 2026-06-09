@extends('layouts.student_layout')

@section('title', 'Grades & Feedback')
@section('page_header', 'Grades & Feedback')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Academic Grades Summary</h1>
            <p class="text-sm text-gray-500 mt-1">Track your performance, points earned, and teacher comments across subjects.</p>
        </div>
        <div class="flex items-center gap-2 bg-maroon-50 border border-maroon-100 rounded-xl px-4 py-2 text-maroon-800 font-semibold text-sm">
            <i class="fa-solid fa-award text-base text-gold-500"></i>
            Enrolled Subjects: {{ count($gradesData) }}
        </div>
    </div>

    {{-- Subjects Grid --}}
    @if(empty($gradesData))
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-500 shadow-sm">
            <i class="fa-solid fa-folder-open text-5xl text-gray-300 mb-4"></i>
            <p class="text-base font-semibold text-gray-700">No grades data available</p>
            <p class="text-sm text-gray-400 mt-1">You are not currently enrolled in active classes with published assignments.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($gradesData as $index => $subject)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    
                    {{-- Subject Card Header --}}
                    <div class="p-6 bg-gray-50/70 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-maroon-100 text-maroon-800 rounded-md">
                                {{ $subject['subject_code'] }}
                            </span>
                            <h2 class="text-lg font-bold text-gray-850 mt-2">{{ $subject['subject_name'] }}</h2>
                            <div class="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-user-tie text-gray-400"></i> {{ $subject['teacher_name'] }}</span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-graduation-cap text-gray-400"></i> {{ $subject['class_section'] }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            {{-- Overall Grade badge --}}
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Overall Grade</span>
                                @if(is_null($subject['overall_percentage']))
                                    <span class="text-sm font-semibold text-gray-400">No Graded Work</span>
                                @else
                                    <div class="flex items-baseline gap-1 mt-0.5">
                                        <span class="text-3xl font-black {{ $subject['overall_percentage'] >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ number_format($subject['overall_percentage'], 1) }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Collapse button --}}
                            <button onclick="toggleSubjectDetails('subject-details-{{ $index }}')"
                                    class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-150 hover:text-gray-800 transition-colors">
                                <i id="subject-arrow-{{ $index }}" class="fa-solid fa-chevron-down text-sm transition-transform duration-250"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Subject Details (Assignments list) --}}
                    <div id="subject-details-{{ $index }}" class="hidden transition-all duration-300">
                        <div class="p-6 border-t border-gray-100 bg-white">
                            @if(empty($subject['assignments']))
                                <p class="text-sm text-gray-450 text-center py-4">No assignments published for this subject yet.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Assignment</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Due Date</th>
                                                <th class="px-4 py-3 class-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Grade</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Comments &amp; Feedback</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-sm">
                                            @foreach($subject['assignments'] as $assignment)
                                                @php
                                                    $badgeClass = match($assignment['status']) {
                                                        'graded' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                        'submitted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                        'unsubmitted' => 'bg-gray-50 text-gray-600 border-gray-100',
                                                        default => 'bg-yellow-50 text-yellow-750 border-yellow-100'
                                                    };
                                                    $statusLabel = match($assignment['status']) {
                                                        'graded' => 'Graded',
                                                        'submitted' => 'Submitted',
                                                        'unsubmitted' => 'Unsubmitted',
                                                        default => 'Pending'
                                                    };
                                                @endphp
                                                <tr class="hover:bg-gray-50/50 transition-colors">
                                                    <td class="px-4 py-4 font-semibold text-gray-800">{{ $assignment['title'] }}</td>
                                                    <td class="px-4 py-4 text-xs text-gray-500 whitespace-nowrap">
                                                        {{ \Carbon\Carbon::parse($assignment['due_date'])->format('M d, Y g:i A') }}
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                                        <span class="px-2.5 py-1 text-xs font-bold uppercase rounded-md border {{ $badgeClass }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-right whitespace-nowrap font-bold text-gray-900">
                                                        @if($assignment['status'] === 'graded')
                                                            <span class="text-emerald-600">{{ number_format($assignment['points_earned'], 1) }}</span>
                                                            <span class="text-xs text-gray-400 font-normal">/ {{ number_format($assignment['max_points'], 1) }}</span>
                                                        @else
                                                            <span class="text-xs text-gray-450 font-normal">— / {{ number_format($assignment['max_points'], 1) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 text-xs text-gray-500 max-w-xs">
                                                        @if($assignment['status'] === 'graded' && $assignment['feedback'])
                                                            <span class="italic text-gray-600 font-medium">"{{ $assignment['feedback'] }}"</span>
                                                        @elseif($assignment['status'] === 'graded')
                                                            <span class="text-gray-400 italic">No teacher comments.</span>
                                                        @else
                                                            <span class="text-gray-300 italic">Not evaluated yet.</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
    function toggleSubjectDetails(id) {
        const detailEl = document.getElementById(id);
        const arrowEl = document.getElementById(id.replace('details', 'arrow'));
        
        if (detailEl) {
            detailEl.classList.toggle('hidden');
            if (arrowEl) {
                arrowEl.classList.toggle('rotate-180');
            }
        }
    }
</script>
@endpush
@endsection
