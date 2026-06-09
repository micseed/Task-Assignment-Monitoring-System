@extends('layouts.teacher_layout')

@section('title', 'Assignments — WMSU TAMS Teacher')
@section('page_title', 'Assignment Manager')

@section('teacher_content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Assignment Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Create, manage, and publish assignments for your classes.</p>
        </div>
        <button onclick="openModal('create-assignment-modal')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700
                       text-white text-sm font-bold rounded-xl hover:brightness-110 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Assignment
        </button>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Class</label>
            <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                <option value="">All Classes</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                        {{ $cls->section }} — {{ $cls->subject->subject_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                <option value="">All Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Type</label>
            <select name="type" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                <option value="">All Types</option>
                <option value="pdf_upload" {{ request('type') === 'pdf_upload' ? 'selected' : '' }}>PDF Upload</option>
                <option value="code_submission" {{ request('type') === 'code_submission' ? 'selected' : '' }}>Code</option>
                <option value="both" {{ request('type') === 'both' ? 'selected' : '' }}>Both</option>
            </select>
        </div>
        <button type="submit"
                class="px-5 py-2 bg-gray-800 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['class_id','status','type']))
            <a href="{{ route('teacher.assignments') }}"
               class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                Clear
            </a>
        @endif
    </form>

    {{-- Assignments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($assignments->isEmpty())
            <div class="px-8 py-16 text-center">
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
                <p class="text-base font-semibold text-gray-400 mb-1">No assignments found</p>
                <p class="text-sm text-gray-400 mb-4">Create your first assignment to get started.</p>
                <button onclick="openModal('create-assignment-modal')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-maroon-800 text-white text-sm font-bold rounded-xl hover:bg-maroon-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Assignment
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-6 py-3.5">Assignment</th>
                            <th class="text-left text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Class</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Type</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Due Date</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Submissions</th>
                            <th class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3.5">Status</th>
                            <th class="text-right text-xs font-bold uppercase tracking-wider text-gray-500 px-6 py-3.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($assignments as $asgn)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $asgn->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $asgn->max_points }} pts &nbsp;·&nbsp;
                                        {{ $asgn->allow_late ? 'Late allowed' : 'No late' }}</p>
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $asgn->academicClass->section }}
                                    <span class="text-gray-400 text-xs">{{ $asgn->academicClass->subject->subject_code }}</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $typeMap = ['pdf_upload' => 'PDF', 'code_submission' => 'Code', 'both' => 'Both'];
                                        $typeBg  = ['pdf_upload' => 'bg-blue-50 text-blue-700', 'code_submission' => 'bg-purple-50 text-purple-700', 'both' => 'bg-teal-50 text-teal-700'];
                                    @endphp
                                    <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-full {{ $typeBg[$asgn->type] ?? '' }}">
                                        {{ $typeMap[$asgn->type] ?? $asgn->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php $overdue = $asgn->due_date->isPast(); @endphp
                                    <p class="text-xs font-semibold {{ $overdue ? 'text-red-500' : 'text-gray-700' }}">
                                        {{ $asgn->due_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-[11px] text-gray-400">{{ $asgn->due_date->format('g:i A') }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('teacher.submissions.inbox') }}?assignment_id={{ $asgn->id }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-maroon-700 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                                        </svg>
                                        {{ $asgn->submissions_count }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($asgn->is_published)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Publish toggle --}}
                                        <form method="POST" action="{{ route('teacher.assignments.publish', $asgn->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    title="{{ $asgn->is_published ? 'Unpublish' : 'Publish' }}"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg border
                                                           {{ $asgn->is_published ? 'border-amber-200 text-amber-500 hover:bg-amber-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}
                                                           transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    @if($asgn->is_published)
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    @endif
                                                </svg>
                                            </button>
                                        </form>
                                        {{-- Edit --}}
                                        <button onclick='openEditModal(@json($asgn))'
                                                title="Edit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500
                                                       hover:text-maroon-700 hover:border-maroon-200 hover:bg-maroon-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                            </svg>
                                        </button>
                                        {{-- Delete --}}
                                        @if($asgn->submissions_count === 0)
                                            <form method="POST" action="{{ route('teacher.assignments.delete', $asgn->id) }}"
                                                  onsubmit="return confirm('Delete assignment: \'{{ addslashes($asgn->title) }}\'? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400
                                                               hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Create Assignment Modal ─────────────────────────────────────────── --}}
    <div id="create-assignment-modal" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center p-4"
         data-modal-backdrop onclick="if(event.target===this)closeModal('create-assignment-modal')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <h3 class="text-base font-extrabold text-gray-800">Create New Assignment</h3>
                <button onclick="closeModal('create-assignment-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('teacher.assignments.store') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Assignment Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Lab Exercise 1: Introduction to Arrays"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Class *</label>
                        <select name="class_id" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                            <option value="">Select class</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->section }} — {{ $cls->subject->subject_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Submission Type *</label>
                        <select name="type" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                            <option value="pdf_upload">PDF Upload</option>
                            <option value="code_submission">Code Submission</option>
                            <option value="both">Both (PDF + Code)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Max Points *</label>
                        <input type="number" name="max_points" min="1" max="1000" value="100" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Due Date &amp; Time *</label>
                        <input type="datetime-local" name="due_date" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Instructions / Description</label>
                        <textarea name="description" rows="4" placeholder="Describe the assignment requirements..."
                                  class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Attach File (optional)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.txt"
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                      file:text-xs file:font-semibold file:bg-maroon-50 file:text-maroon-700 hover:file:bg-maroon-100">
                        <p class="text-[11px] text-gray-400 mt-1">PDF, DOC, ZIP or TXT up to 10MB</p>
                    </div>
                    <div class="flex items-end gap-6">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="allow_late" class="w-4 h-4 accent-maroon-700 rounded">
                            <span class="text-sm text-gray-600 font-medium">Allow late submissions</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_published" class="w-4 h-4 accent-maroon-700 rounded">
                            <span class="text-sm text-gray-600 font-medium">Publish immediately</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal('create-assignment-modal')"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all">
                        Create Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Edit Assignment Modal ─────────────────────────────────────────── --}}
    <div id="edit-assignment-modal" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <h3 class="text-base font-extrabold text-gray-800">Edit Assignment</h3>
                <button onclick="closeModal('edit-assignment-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-assignment-form" method="POST" action="" class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Assignment Title *</label>
                        <input type="text" id="edit_title" name="title" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Submission Type *</label>
                        <select id="edit_type" name="type" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                            <option value="pdf_upload">PDF Upload</option>
                            <option value="code_submission">Code Submission</option>
                            <option value="both">Both (PDF + Code)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Max Points *</label>
                        <input type="number" id="edit_max_points" name="max_points" min="1" max="1000" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Due Date &amp; Time *</label>
                        <input type="datetime-local" id="edit_due_date" name="due_date" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Instructions / Description</label>
                        <textarea id="edit_description" name="description" rows="4"
                                  class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-maroon-700/20 resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" id="edit_allow_late" name="allow_late" class="w-4 h-4 accent-maroon-700 rounded">
                            <span class="text-sm text-gray-600 font-medium">Allow late submissions</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal('edit-assignment-modal')"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openEditModal(asgn) {
        document.getElementById('edit-assignment-form').action = '/teacher/assignments/' + asgn.id + '/update';
        document.getElementById('edit_title').value       = asgn.title;
        document.getElementById('edit_type').value        = asgn.type;
        document.getElementById('edit_max_points').value  = asgn.max_points;
        document.getElementById('edit_description').value = asgn.description || '';
        document.getElementById('edit_allow_late').checked= asgn.allow_late;
        // Format due_date for datetime-local
        if (asgn.due_date) {
            const d = new Date(asgn.due_date);
            const pad = n => String(n).padStart(2,'0');
            document.getElementById('edit_due_date').value =
                d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()) +
                'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        }
        openModal('edit-assignment-modal');
    }
</script>
@endpush
