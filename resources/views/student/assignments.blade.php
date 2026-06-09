@extends('layouts.student_layout')

@section('title', 'My Assignments')
@section('page_header', 'My Assignments')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- Filtering Tabs --}}
    <div class="p-6 border-b border-gray-200 bg-gray-50/70">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap gap-2">
                @php
                    $tabs = [
                        ['id' => null, 'label' => 'All', 'count' => $totalCount, 'icon' => 'fa-folder-open'],
                        ['id' => 'pending', 'label' => 'Pending', 'count' => $pendingCount, 'icon' => 'fa-clock'],
                        ['id' => 'submitted', 'label' => 'Submitted', 'count' => $submittedCount, 'icon' => 'fa-paper-plane'],
                        ['id' => 'graded', 'label' => 'Graded', 'count' => $gradedCount, 'icon' => 'fa-award'],
                        ['id' => 'overdue', 'label' => 'Overdue', 'count' => $overdueCount, 'icon' => 'fa-triangle-exclamation'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    @php
                        $isActive = ($statusFilter === $tab['id']);
                        $tabUrl = route('student.assignments', $tab['id'] ? ['status' => $tab['id']] : []);
                    @endphp
                    <a href="{{ $tabUrl }}"
                       class="flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg border transition-all duration-150
                              {{ $isActive 
                                  ? 'bg-maroon-800 text-white border-maroon-800 shadow-sm shadow-maroon-800/10' 
                                  : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        <i class="fa-solid {{ $tab['icon'] }} opacity-70"></i>
                        <span>{{ $tab['label'] }}</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold
                                     {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>
            
            <div class="text-right text-xs text-gray-400 font-medium whitespace-nowrap">
                Showing {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} tasks
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assignment</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Class</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->first();
                        $status = $submission ? $submission->status : 'pending';
                        
                        $statusClass = match($status) {
                            'graded' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'unsubmitted' => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800 border-red-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            default => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800 border-red-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200'
                        };

                        $statusText = match($status) {
                            'graded' => 'Graded',
                            'submitted' => 'Submitted',
                            'unsubmitted' => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'Overdue' : 'Pending',
                            default => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'Overdue' : 'Pending'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $assignment->title }}</div>
                            <div class="text-xs text-gray-500 mt-1 capitalize">{{ str_replace('_', ' ', $assignment->type) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $assignment->academicClass->subject->subject_name }}</div>
                            <div class="text-xs text-gray-500">{{ $assignment->academicClass->section }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($assignment->due_date)->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('student.assignments.detail', $assignment->id) }}" class="text-maroon-600 hover:text-maroon-900 bg-maroon-50 hover:bg-maroon-100 px-3 py-1.5 rounded transition-colors">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <p>You have no assignments at the moment.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($assignments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $assignments->links() }}
    </div>
    @endif
</div>
@endsection
