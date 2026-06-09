<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Dashboard — WMSU TAMS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
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
            <img src="{{ asset('images/wmsu_logo.png') }}" alt="WMSU" class="w-11 h-11 object-contain flex-shrink-0" />
            <div>
                <p class="text-base font-extrabold tracking-tight text-white uppercase leading-none">WMSU TAMS</p>
                <p class="text-[10px] font-bold text-gold-500 uppercase tracking-widest mt-0.5">Dean Dashboard</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-1">
            @php
                $navItems = [
                    [
                        'route' => 'admin.dashboard',
                        'label' => 'Home Overview',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />'
                    ],
                    [
                        'route' => 'admin.users',
                        'label' => 'User Management',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />'
                    ],
                    [
                        'route' => 'admin.classes',
                        'label' => 'Classes & Subjects',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />'
                    ],
                    [
                        'route' => 'admin.reports',
                        'label' => 'Department Reports',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />'
                    ],
                    [
                        'route' => 'admin.audit_logs',
                        'label' => 'Audit Activity Logs',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />'
                    ],
                    [
                        'route' => 'admin.settings',
                        'label' => 'System Settings',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />'
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                          {{ $isActive
                                ? 'bg-gold-500/15 border-l-4 border-gold-500 pl-3 text-gold-400 font-semibold'
                                : 'text-white/75 hover:bg-white/8 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 flex-shrink-0"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        {!! $item['icon'] !!}
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
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
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400 hidden sm:block">Dean</span>
                    <span class="text-gray-300 hidden sm:block">/</span>
                    <span class="text-sm font-bold text-gray-700">@yield('page_title', 'Overview')</span>
                </div>
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
                        <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wider mt-0.5">Dean / Admin</p>
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

            @yield('admin_content')
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
