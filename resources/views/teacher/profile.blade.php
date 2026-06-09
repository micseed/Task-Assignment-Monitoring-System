@extends('layouts.teacher_layout')

@section('title', 'Profile & Settings — WMSU TAMS Teacher')
@section('page_title', 'Profile & Settings')

@section('teacher_content')

    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Profile &amp; Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your personal information and account preferences.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Profile Card --}}
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-maroon-900 to-maroon-700 px-6 pt-10 pb-12 relative">
                    <div class="absolute bottom-0 left-0 right-0 h-6 bg-white rounded-t-3xl"></div>
                </div>
                <div class="px-6 pb-6 -mt-10 relative text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-maroon-800 to-maroon-600
                                text-white text-2xl font-extrabold flex items-center justify-center uppercase
                                mx-auto border-4 border-white shadow-lg">
                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                    </div>
                    <h3 class="mt-3 text-base font-extrabold text-gray-800">{{ $teacher->name }}</h3>
                    <p class="text-sm text-maroon-700 font-semibold">Subject Teacher</p>
                    @if($teacher->department)
                        <p class="text-xs text-gray-400 mt-1">{{ $teacher->department->name }}</p>
                    @endif
                    <div class="mt-4 pt-4 border-t border-gray-100 text-left space-y-2.5">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <span class="truncate text-xs">{{ $teacher->email }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-xs {{ $teacher->is_active ? 'text-emerald-600 font-semibold' : 'text-red-500' }}">
                                {{ $teacher->is_active ? 'Active Account' : 'Account Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Forms --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-maroon-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Personal Information</h3>
                        <p class="text-xs text-gray-400">Update your name and email address</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('teacher.profile.update') }}" class="px-6 py-5 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}" required
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5
                                          focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}" required
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5
                                          focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5
                                          focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                        </div>
                        @if($teacher->department)
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Department</label>
                                <input type="text" value="{{ $teacher->department->name }}" readonly disabled
                                       class="w-full text-sm border border-gray-100 rounded-xl px-4 py-2.5 bg-gray-50 text-gray-500 cursor-not-allowed">
                                <p class="text-[11px] text-gray-400 mt-1">Contact your administrator to change your department assignment.</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-end pt-1">
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-maroon-800 to-maroon-700
                                       rounded-xl hover:brightness-110 transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Change Password</h3>
                        <p class="text-xs text-gray-400">Choose a strong password of at least 8 characters</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('teacher.profile.password') }}" class="px-6 py-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Current Password *</label>
                        <input type="password" name="current_password" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5
                                      focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">New Password *</label>
                            <input type="password" name="password" required minlength="8"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5
                                          focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Confirm New Password *</label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5
                                          focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-bold text-white bg-amber-500 rounded-xl hover:bg-amber-400 transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Account Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800">Account Information</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Role</p>
                        <p class="font-semibold text-gray-700 capitalize">{{ $teacher->role }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Status</p>
                        <p class="font-semibold {{ $teacher->is_active ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Member Since</p>
                        <p class="font-semibold text-gray-700">{{ $teacher->created_at->format('M Y') }}</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-400">
                        To change your role, department, or account status, please contact your system administrator.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
