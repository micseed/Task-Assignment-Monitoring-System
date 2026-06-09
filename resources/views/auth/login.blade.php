@extends('layouts.app')

@section('title', 'Sign In — WMSU TAM System')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    {{-- ─── Left Branding Panel ─── --}}
    <div class="hidden lg:flex lg:flex-[1.2] relative overflow-hidden items-center justify-center">
        {{-- Campus background --}}
        <div class="absolute inset-0 bg-cover bg-center bg-[url('{{ asset('images/wmsu_campus.jpg') }}')] opacity-40"></div>
        {{-- Maroon gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-br from-maroon-900/90 via-maroon-800/88 to-maroon-700/85"></div>
        {{-- Gold glow orb --}}
        <div class="absolute -top-24 -left-24 w-[500px] h-[500px] rounded-full bg-gold-500/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-gold-500/5 blur-3xl"></div>

        <div class="relative z-10 max-w-md text-center px-10 animate-[fadeInUp_0.8s_ease_both]">
            <img src="{{ asset('images/wmsu_logo.png') }}" alt="WMSU Logo"
                 class="w-36 h-36 object-contain mx-auto mb-8 drop-shadow-2xl transition-transform duration-500 hover:scale-105 hover:rotate-3" />

            <h1 class="text-4xl font-extrabold tracking-tight text-white uppercase leading-none mb-1">WMSU</h1>
            <p class="text-lg font-bold text-gold-500 uppercase tracking-widest mb-6">Task Assignment &amp; Monitoring</p>

            <div class="w-20 h-1 bg-gold-500 rounded-full mx-auto mb-6"></div>

            <p class="text-sm text-white/75 leading-relaxed">
                Welcome to the Western Mindanao State University academic portal.
                Seamlessly coordinate assignments, upload code files, track evaluations,
                and sync deadlines in one centralized workspace.
            </p>

            {{-- Feature pills --}}
            <div class="flex flex-wrap justify-center gap-2 mt-8">
                <span class="flex items-center gap-1.5 bg-white/10 border border-white/15 text-white/80 text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5 text-gold-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Task Tracking
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 border border-white/15 text-white/80 text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5 text-gold-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Student Monitoring
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 border border-white/15 text-white/80 text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5 text-gold-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    Grade Reports
                </span>
            </div>
        </div>
    </div>

    {{-- ─── Right Form Panel ─── --}}
    <div class="flex flex-1 items-center justify-center px-6 py-12 bg-white lg:shadow-[-10px_0_30px_rgba(0,0,0,0.04)]">
        <div class="w-full max-w-[400px] animate-[fadeInUp_0.7s_ease_both]">

            {{-- Mobile Logo (visible on small screens only) --}}
            <div class="flex flex-col items-center mb-8 lg:hidden">
                <img src="{{ asset('images/wmsu_logo.png') }}" alt="WMSU Logo" class="w-20 h-20 object-contain mb-3" />
                <h2 class="text-xl font-extrabold text-maroon-800 uppercase tracking-tight">WMSU TAM System</h2>
            </div>

            {{-- Form heading --}}
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-maroon-800 tracking-tight">Sign In</h2>
                <p class="mt-2 text-sm text-gray-500">Welcome back! Enter your portal credentials below.</p>
            </div>

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <ul class="list-disc pl-3 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Email Address</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@wmsu.edu.ph"
                            autocomplete="email"
                            required
                            autofocus
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all focus:border-maroon-700 focus:bg-white focus:ring-3 focus:ring-maroon-700/15 @error('email') border-red-400 @enderror"
                        />
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all focus:border-maroon-700 focus:bg-white focus:ring-3 focus:ring-maroon-700/15 @error('password') border-red-400 @enderror"
                        />
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" id="remember" name="remember"
                               class="w-4 h-4 rounded border-gray-300 accent-maroon-700 cursor-pointer" />
                        <span class="text-sm text-gray-500">Remember my login</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button id="btn-login" type="submit"
                        class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-maroon-800 to-maroon-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-maroon-800/25 transition-all duration-200 hover:brightness-110 hover:shadow-maroon-800/40 hover:shadow-xl active:scale-[0.98] active:shadow-md mt-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Sign In to Portal
                </button>
            </form>

        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
