@extends('layouts.student_layout')

@section('title', 'My Profile')
@section('page_header', 'Profile & Settings')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Profile Info Card -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-maroon-800 h-24"></div>
            <div class="px-6 pb-6 relative">
                <div class="h-20 w-20 rounded-full border-4 border-white bg-maroon-600 text-white flex items-center justify-center text-2xl font-bold absolute -top-10 shadow-md">
                    {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                </div>
                
                <div class="pt-12">
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">Student</p>
                    <p class="text-gray-600 text-sm mt-3 flex items-center">
                        <i class="fa-solid fa-envelope w-5 text-gray-400"></i>
                        {{ Auth::user()->email }}
                    </p>
                    <p class="text-gray-600 text-sm mt-2 flex items-center">
                        <i class="fa-solid fa-building w-5 text-gray-400"></i>
                        {{ Auth::user()->department->name ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile & Password -->
    <div class="md:col-span-2 space-y-6">
        
        <!-- Update Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Update Profile</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring-maroon-500 sm:text-sm">
                            @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring-maroon-500 sm:text-sm">
                            @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring-maroon-500 sm:text-sm">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2 mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Notification Preferences</h4>
                            <div class="space-y-3">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="email_notifications" value="1"
                                           class="mt-1 w-4 h-4 rounded border-gray-300 accent-maroon-700 cursor-pointer"
                                           {{ old('email_notifications', Auth::user()->email_notifications) ? 'checked' : '' }} />
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700 block leading-none">Email Alerts</span>
                                        <span class="text-xs text-gray-400">Receive email reminders when new assignments are published or evaluated</span>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="calendar_notifications" value="1"
                                           class="mt-1 w-4 h-4 rounded border-gray-300 accent-maroon-700 cursor-pointer"
                                           {{ old('calendar_notifications', Auth::user()->calendar_notifications) ? 'checked' : '' }} />
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700 block leading-none">Google Calendar Sync</span>
                                        <span class="text-xs text-gray-400">Automatically sync upcoming task deadlines with your Google Calendar reminders</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-right">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-maroon-700 hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Change Password</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('student.profile.password') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring-maroon-500 sm:text-sm">
                            @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring-maroon-500 sm:text-sm">
                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-maroon-500 focus:ring-maroon-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="mt-5 text-right">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
