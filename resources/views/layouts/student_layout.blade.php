<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Student Dashboard — WMSU TAMS')</title>
    <meta name="description" content="WMSU Task Assignment & Monitoring System — Student Portal" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        maroon: {
                            50:  'hsl(348, 80%, 97%)',
                            100: 'hsl(348, 80%, 92%)',
                            200: 'hsl(348, 80%, 82%)',
                            300: 'hsl(348, 80%, 68%)',
                            400: 'hsl(348, 80%, 52%)',
                            500: 'hsl(348, 83%, 38%)',
                            600: 'hsl(348, 83%, 30%)',
                            700: 'hsl(348, 83%, 22%)',
                            800: 'hsl(348, 85%, 16%)',
                            900: 'hsl(348, 85%, 10%)',
                        },
                        gold: {
                            300: 'hsl(45, 95%, 70%)',
                            400: 'hsl(45, 95%, 60%)',
                            500: 'hsl(45, 95%, 50%)',
                            600: 'hsl(45, 95%, 42%)',
                        }
                    }
                }
            }
        }
    </script>
    @stack('styles')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .page-animate { animation: fadeInUp 0.4s ease both; }
        /* Sidebar scrollbar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800 flex overflow-x-hidden min-h-screen">

    {{-- ─────────────────── Sidebar ─────────────────── --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col
                  bg-gradient-to-b from-maroon-900 to-maroon-800
                  shadow-[4px_0_24px_rgba(0,0,0,0.18)]
                  transition-transform duration-300
                  -translate-x-full lg:translate-x-0">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10 flex-shrink-0">
            <img src="{{ asset('images/wmsu_logo.png') }}" alt="WMSU" class="w-11 h-11 object-contain flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?name=WMSU&background=random'" />
            <div>
                <p class="text-base font-extrabold tracking-tight text-white uppercase leading-none">WMSU TAMS</p>
                <p class="text-[10px] font-bold text-gold-500 uppercase tracking-widest mt-0.5">Student Portal</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-1">

            {{-- Overview --}}
            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('student.dashboard')
                           ? 'bg-gold-500/15 border-l-4 border-gold-500 pl-3 text-gold-400 font-semibold'
                           : 'text-white/75 hover:bg-white/8 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                <span>Dashboard Overview</span>
            </a>

            {{-- My Assignments --}}
            <a href="{{ route('student.assignments') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('student.assignments*')
                           ? 'bg-gold-500/15 border-l-4 border-gold-500 pl-3 text-gold-400 font-semibold'
                           : 'text-white/75 hover:bg-white/8 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <span>My Assignments</span>
            </a>

            {{-- Profile Settings --}}
            <a href="{{ route('student.profile') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('student.profile*')
                           ? 'bg-gold-500/15 border-l-4 border-gold-500 pl-3 text-gold-400 font-semibold'
                           : 'text-white/75 hover:bg-white/8 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>Profile &amp; Settings</span>
            </a>

        </nav>

        {{-- Sidebar Footer --}}
        <div class="flex-shrink-0 px-5 py-4 border-t border-white/10 text-center">
            <p class="text-[11px] text-white/35">&copy; {{ date('Y') }} WMSU TAMS</p>
        </div>
    </aside>

    {{-- ─────────────────── Main Wrapper ─────────────────── --}}
    <div class="flex flex-col flex-1 min-w-0 lg:ml-72 transition-all duration-300">

        {{-- Top Header --}}
        <header class="sticky top-0 z-40 flex items-center justify-between h-[70px] px-6 lg:px-10
                        bg-white border-b border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                {{-- Mobile sidebar toggle --}}
                <button id="menu-toggle"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200
                               text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <h1 class="text-base font-bold text-gray-800 tracking-tight">@yield('page_header', 'WMSU Academic Monitoring')</h1>
            </div>

            <div class="flex items-center gap-4">
                {{-- User avatar + name --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-700 text-white
                                flex items-center justify-center text-sm font-bold uppercase
                                ring-2 ring-gold-500 ring-offset-1">
                        {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wider mt-0.5">Student</p>
                    </div>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-500
                                   border border-gray-200 rounded-lg transition-colors
                                   hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </header>

        {{-- ─── Page Content ─── --}}
        <main class="flex-1 p-6 lg:p-10 page-animate">

            {{-- Flash Alerts --}}
            @if(session('success'))
                <div class="flex items-center justify-between gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800
                             rounded-xl px-4 py-3.5 mb-6 text-sm font-medium" id="success-alert">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button class="text-emerald-500 hover:text-emerald-700 ml-2" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center justify-between gap-3 bg-red-50 border border-red-200 text-red-800
                             rounded-xl px-4 py-3.5 mb-6 text-sm font-medium" id="error-alert">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                    <button class="text-red-500 hover:text-red-700 ml-2" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="flex items-start justify-between gap-3 bg-red-50 border border-red-200 text-red-800
                             rounded-xl px-4 py-3.5 mb-6 text-sm" id="validation-alert">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <div>
                            <p class="font-semibold">Please fix the errors below:</p>
                            <ul class="mt-1.5 list-disc pl-4 space-y-0.5 text-xs text-red-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button class="text-red-500 hover:text-red-700 flex-shrink-0 mt-0.5" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- ─── Scripts ─── --}}
    <script>
        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle && menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function (e) {
            if (window.innerWidth < 1024 && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });

        // Modal helpers
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
        }
        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        }
        // Close modal on backdrop click
        window.addEventListener('click', function (e) {
            if (e.target.dataset.modalBackdrop) closeModal(e.target.id);
        });
    </script>
    @stack('scripts')
</body>
</html>
