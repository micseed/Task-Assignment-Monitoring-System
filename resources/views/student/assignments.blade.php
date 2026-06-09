@extends('layouts.student_layout')

@section('title', 'My Assignments')
@section('page_header', 'My Assignments')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">All Tasks</h2>
        
        <!-- Filters could be added here in the future -->
        <div class="mt-3 sm:mt-0">
            <span class="text-sm text-gray-500">Showing {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }}</span>
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
                            'graded' => 'bg-green-100 text-green-800 border-green-200',
                            'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'draft' => 'bg-gray-100 text-gray-800 border-gray-200',
                            default => Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800 border-red-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200'
                        };

                        $statusText = match($status) {
                            'graded' => 'Graded',
                            'submitted' => 'Submitted',
                            'draft' => 'Draft',
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
