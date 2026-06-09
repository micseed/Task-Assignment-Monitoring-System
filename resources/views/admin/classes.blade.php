@extends('layouts.admin_layout')

@section('title', 'Class & Subject Setup — WMSU TAMS Admin')
@section('page_title', 'Classes & Subjects')

@section('admin_content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Classes &amp; Subjects Setup</h2>
            <p class="text-sm text-gray-500 mt-1">Configure active sections, assign teachers, catalog subjects, and manage student enrollments.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="openModal('addSubjectModal')"
                    class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 bg-white text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Subject
            </button>
            <button onclick="openModal('addClassModal')"
                    class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700
                           text-white text-sm font-semibold rounded-xl shadow-sm hover:brightness-110 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Class
            </button>
        </div>
    </div>

    {{-- Main grid: Left (Classes + Roster) | Right (Subjects) --}}
    <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-6">

        {{-- Left: Academic Classes --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Academic Classes List</h3>
                    <span class="text-xs text-gray-400">Click a class to manage students</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Section</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Teacher</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Term</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Students</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($classes as $class)
                                <tr id="class-row-{{ $class->id }}"
                                    class="class-row cursor-pointer transition-colors hover:bg-amber-50/60"
                                    onclick="selectClass({{ json_encode($class) }}, {{ json_encode($class->students) }})">
                                    <td class="px-5 py-4 font-semibold text-gray-800">{{ $class->section }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-800">{{ $class->subject->subject_code }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $class->subject->subject_name }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600 text-sm">
                                        {{ $class->subject->teacher ? $class->subject->teacher->name : 'Unassigned' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="text-xs font-medium text-gray-700">{{ $class->school_year }}</p>
                                        <p class="text-xs text-gray-400">{{ $class->semester }} Sem</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            {{ $class->students_count }} Enrolled
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        <p class="text-sm text-gray-400 font-medium">No academic classes defined. Create a class to get started.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Class Roster Panel --}}
            <div id="rosterPanel"
                 class="rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 min-h-[250px] flex flex-col items-center justify-center p-6 transition-all">
                {{-- Empty State --}}
                <div id="rosterEmptyState" class="text-center">
                    <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <h3 class="font-bold text-gray-600 mb-1">Class Roster View</h3>
                    <p class="text-xs text-gray-400 max-w-[260px] leading-relaxed">
                        Click on any class in the table above to view enrolled students and add new student enrollments.
                    </p>
                </div>

                {{-- Roster Content (hidden by default) --}}
                <div id="rosterContent" class="hidden w-full">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-200">
                        <div>
                            <h3 id="rosterTitle" class="text-base font-extrabold text-gray-800">Class Section</h3>
                            <span id="rosterSubtitle" class="text-xs text-gray-400 mt-0.5 block">Subject details</span>
                        </div>
                        <button onclick="openModal('enrollStudentModal')"
                                class="flex items-center gap-1.5 px-3 py-2 bg-gradient-to-r from-maroon-800 to-maroon-700 text-white text-xs font-semibold rounded-xl hover:brightness-110 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Enroll Student
                        </button>
                    </div>

                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Enrolled Students</p>
                    <div id="rosterStudentList"
                         class="max-h-72 overflow-y-auto divide-y divide-gray-100 border border-gray-100 rounded-xl bg-white">
                        {{-- Dynamically populated --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Subject Catalog --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Subject Catalog</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Code &amp; Name</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Teacher</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($subjects as $subj)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-maroon-700 text-sm">{{ $subj->subject_code }}</p>
                                        <p class="text-sm text-gray-700 font-medium mt-0.5">{{ $subj->subject_name }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">Dept: {{ $subj->department->code }} | {{ $subj->semester }} Sem</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($subj->teacher)
                                            <span class="text-sm font-medium text-gray-700">{{ $subj->teacher->name }}</span>
                                        @else
                                            <span class="flex items-center gap-1 text-amber-600 italic text-xs font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                                </svg>
                                                Unassigned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <button onclick="triggerAssignModal({{ json_encode($subj) }})"
                                                class="flex items-center gap-1.5 ml-auto px-3 py-1.5 text-xs font-semibold text-gray-600
                                                       border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                            Assign
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <p class="text-sm text-gray-400">No subjects cataloged yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 1. CREATE CLASS MODAL ── --}}
    <div id="addClassModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Create Class Section</h3>
                    <button type="button" onclick="closeModal('addClassModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="add_class_subject_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Subject Catalog Item</label>
                        <select id="add_class_subject_id" name="subject_id" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                            <option value="">Select a Subject...</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->subject_code }} — {{ $subj->subject_name }} ({{ $subj->department->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="add_class_section" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Section Name</label>
                        <input type="text" id="add_class_section" name="section" placeholder="e.g. BSIT 3-A" required
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add_class_school_year" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">School Year</label>
                            <input type="text" id="add_class_school_year" name="school_year" required
                                   value="{{ \App\Models\Setting::get('school_year', '2024-2025') }}"
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                        <div>
                            <label for="add_class_semester" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Semester</label>
                            <select id="add_class_semester" name="semester" required
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="1st">1st Sem</option>
                                <option value="2nd" selected>2nd Sem</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" onclick="closeModal('addClassModal')" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all shadow-sm">Create Section</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 2. CREATE SUBJECT MODAL ── --}}
    <div id="addSubjectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <form method="POST" action="{{ route('admin.subjects.store') }}">
                @csrf
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Catalog New Subject</h3>
                    <button type="button" onclick="closeModal('addSubjectModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-[1fr_2fr] gap-4">
                        <div>
                            <label for="add_subject_code" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Subject Code</label>
                            <input type="text" id="add_subject_code" name="subject_code" placeholder="e.g. IT301" required
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                        <div>
                            <label for="add_subject_name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Subject Name</label>
                            <input type="text" id="add_subject_name" name="subject_name" placeholder="e.g. Web Development" required
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add_subject_dept" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Department</label>
                            <select id="add_subject_dept" name="department_id" required
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="add_subject_teacher" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Teacher</label>
                            <select id="add_subject_teacher" name="teacher_id"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="">Leave Unassigned</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add_subject_sy" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">School Year</label>
                            <input type="text" id="add_subject_sy" name="school_year" required
                                   value="{{ \App\Models\Setting::get('school_year', '2024-2025') }}"
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                        <div>
                            <label for="add_subject_sem" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Semester</label>
                            <select id="add_subject_sem" name="semester" required
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="1st">1st Sem</option>
                                <option value="2nd" selected>2nd Sem</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" onclick="closeModal('addSubjectModal')" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all shadow-sm">Create Subject</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 3. ENROLL STUDENT MODAL ── --}}
    <div id="enrollStudentModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <form method="POST" action="{{ route('admin.classes.enroll') }}">
                @csrf
                <input type="hidden" id="enroll_class_id" name="class_id" value="" />
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Enroll Student in Class</h3>
                    <button type="button" onclick="closeModal('enrollStudentModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <span><strong>Enrolling into:</strong> <span id="enrollSectionLabel">Section</span></span>
                    </div>
                    <div>
                        <label for="enroll_student_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Select Student</label>
                        <select id="enroll_student_id" name="student_id" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                            <option value="">Choose a Student...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" onclick="closeModal('enrollStudentModal')" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all shadow-sm">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 4. ASSIGN TEACHER MODAL ── --}}
    <div id="assignTeacherModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <form id="assignTeacherForm" method="POST" action="">
                @csrf
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Assign Subject Teacher</h3>
                    <button type="button" onclick="closeModal('assignTeacherModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Subject</label>
                        <input type="text" id="assign_subject_label" readonly
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-100 text-sm text-gray-600 cursor-default" />
                    </div>
                    <div>
                        <label for="assign_teacher_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Assigned Faculty Member</label>
                        <select id="assign_teacher_id" name="teacher_id"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                            <option value="">None / Leave Unassigned</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" onclick="closeModal('assignTeacherModal')" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all shadow-sm">Assign Faculty</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let activeClassId = null;

    function selectClass(classData, students) {
        activeClassId = classData.id;

        // Highlight active row
        document.querySelectorAll('.class-row').forEach(row => {
            row.classList.remove('bg-amber-50/60', 'ring-2', 'ring-inset', 'ring-gold-500/30');
        });
        const activeRow = document.getElementById(`class-row-${classData.id}`);
        if (activeRow) activeRow.classList.add('bg-amber-50/60');

        // Switch roster panel to active mode
        const panel = document.getElementById('rosterPanel');
        panel.classList.remove('border-dashed', 'border-gray-200', 'bg-gray-50', 'items-center', 'justify-center');
        panel.classList.add('border-solid', 'border-gray-100', 'bg-white', 'shadow-sm', 'items-stretch', 'p-6');

        document.getElementById('rosterEmptyState').classList.add('hidden');
        document.getElementById('rosterContent').classList.remove('hidden');

        // Populate header
        document.getElementById('rosterTitle').innerText = classData.section;
        document.getElementById('rosterSubtitle').innerText = `${classData.subject.subject_code} — ${classData.subject.subject_name}`;

        // Set enroll form fields
        document.getElementById('enroll_class_id').value = classData.id;
        document.getElementById('enrollSectionLabel').innerText = `${classData.section} (${classData.subject.subject_code})`;

        // Populate student list
        const studentListDiv = document.getElementById('rosterStudentList');
        studentListDiv.innerHTML = '';

        if (students.length === 0) {
            studentListDiv.innerHTML = `
                <div class="px-4 py-8 text-center text-sm text-gray-400 italic">
                    No students currently enrolled in this section.
                </div>
            `;
        } else {
            students.forEach(student => {
                const enrollmentId = student.pivot ? student.pivot.id : null;
                const unenrollBtn = enrollmentId
                    ? `<form method="POST" action="/admin/classes/unenroll/${enrollmentId}" onsubmit="return confirm('Remove this student from this class?')" class="m-0">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                           <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors px-2 py-1 rounded-lg hover:bg-red-50">
                               Remove
                           </button>
                       </form>`
                    : '';

                const item = document.createElement('div');
                item.className = 'flex items-center justify-between px-4 py-3';
                item.innerHTML = `
                    <div>
                        <p class="text-sm font-semibold text-gray-800">${student.first_name} ${student.last_name}</p>
                        <p class="text-xs text-gray-400 mt-0.5">${student.email}</p>
                    </div>
                    ${unenrollBtn}
                `;
                studentListDiv.appendChild(item);
            });
        }
    }

    function triggerAssignModal(subject) {
        document.getElementById('assignTeacherForm').action = `/admin/subjects/${subject.id}/assign`;
        document.getElementById('assign_subject_label').value = `${subject.subject_code} — ${subject.subject_name}`;
        document.getElementById('assign_teacher_id').value = subject.teacher_id || '';
        openModal('assignTeacherModal');
    }
</script>
@endpush
