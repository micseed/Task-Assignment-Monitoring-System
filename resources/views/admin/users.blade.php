@extends('layouts.admin_layout')

@section('title', 'User Management — WMSU TAMS Admin')

@section('admin_content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">User Accounts Management</h2>
            <p class="text-sm text-gray-500 mt-1">Manage student and teacher credentials, roles, and department affiliations.</p>
        </div>
        <button onclick="openModal('addUserModal')"
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700
                       text-white text-sm font-semibold rounded-xl shadow-sm hover:brightness-110 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New User
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('admin.users') }}"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="lg:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Search</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Search name or email..."
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm text-gray-700 placeholder-gray-400 outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                </div>
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Role</label>
                <select id="role" name="role"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm text-gray-700 outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin / Dean</option>
                </select>
            </div>

            <div>
                <label for="department_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Department</label>
                <select id="department_id" name="department_id"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm text-gray-700 outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role', 'department_id', 'status']))
                    <a href="{{ route('admin.users') }}"
                       class="flex items-center justify-center w-10 h-10 border border-gray-200 rounded-xl text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-700
                                                text-white text-xs font-bold flex items-center justify-center flex-shrink-0 uppercase">
                                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Admin
                                    </span>
                                @elseif($user->isTeacher())
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Teacher
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>Student
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->department ? $user->department->code : '—' }}</td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Deactivated
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="triggerEditModal({{ json_encode($user) }})"
                                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600
                                                   border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}" class="m-0">
                                        @csrf
                                        @if($user->is_active)
                                            <button type="submit" {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600
                                                           border border-red-200 rounded-lg hover:bg-red-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                                </svg>
                                                Deactivate
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-maroon-700
                                                           border border-maroon-200 rounded-lg hover:bg-maroon-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                                </svg>
                                                Activate
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <p class="text-sm text-gray-400 font-medium">No user accounts matching the filters found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} accounts
                </span>
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- ── ADD USER MODAL ── --}}
    <div id="addUserModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Create User Account</h3>
                    <button type="button" onclick="closeModal('addUserModal')"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add_first_name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">First Name</label>
                            <input type="text" id="add_first_name" name="first_name" required
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                        <div>
                            <label for="add_last_name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Last Name</label>
                            <input type="text" id="add_last_name" name="last_name" required
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                    </div>
                    <div>
                        <label for="add_email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Email Address</label>
                        <input type="email" id="add_email" name="email" placeholder="user@wmsu.edu.ph" required
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                    </div>
                    <div>
                        <label for="add_password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Password</label>
                        <input type="password" id="add_password" name="password" placeholder="Min 6 characters" required
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add_role" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">System Role</label>
                            <select id="add_role" name="role" required
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                                <option value="admin">Admin / Dean</option>
                            </select>
                        </div>
                        <div>
                            <label for="add_department_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Department</label>
                            <select id="add_department_id" name="department_id"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="">None / Administrative</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer select-none mt-2">
                        <input type="checkbox" id="add_is_active" name="is_active" value="1" checked
                               class="w-4 h-4 rounded border-gray-300 accent-maroon-700" />
                        <span class="text-sm text-gray-600 font-medium">Account is Active and Enabled</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" onclick="closeModal('addUserModal')"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all shadow-sm">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── EDIT USER MODAL ── --}}
    <div id="editUserModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <form id="editUserForm" method="POST" action="">
                @csrf
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Edit User Account</h3>
                    <button type="button" onclick="closeModal('editUserModal')"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_first_name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">First Name</label>
                            <input type="text" id="edit_first_name" name="first_name" required
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                        <div>
                            <label for="edit_last_name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Last Name</label>
                            <input type="text" id="edit_last_name" name="last_name" required
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                        </div>
                    </div>
                    <div>
                        <label for="edit_email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Email Address</label>
                        <input type="email" id="edit_email" name="email" required
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                    </div>
                    <div>
                        <label for="edit_password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">New Password</label>
                        <input type="password" id="edit_password" name="password" placeholder="Leave blank to keep existing"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_role" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">System Role</label>
                            <select id="edit_role" name="role" required
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                                <option value="admin">Admin / Dean</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_department_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Department</label>
                            <select id="edit_department_id" name="department_id"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15 focus:bg-white transition-all">
                                <option value="">None / Administrative</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer select-none mt-2">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1"
                               class="w-4 h-4 rounded border-gray-300 accent-maroon-700" />
                        <span class="text-sm text-gray-600 font-medium">Account is Active and Enabled</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" onclick="closeModal('editUserModal')"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-maroon-800 to-maroon-700 rounded-xl hover:brightness-110 transition-all shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function triggerEditModal(user) {
        document.getElementById('editUserForm').action = `/admin/users/${user.id}/update`;
        document.getElementById('edit_first_name').value = user.first_name;
        document.getElementById('edit_last_name').value = user.last_name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_department_id').value = user.department_id || '';
        document.getElementById('edit_is_active').checked = !!user.is_active;
        openModal('editUserModal');
    }
</script>
@endpush
