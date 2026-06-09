<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Teacher Dashboard — WMSU TAMS')</title>
    <meta name="description" content="WMSU Task Assignment & Monitoring System — Subject Teacher Portal" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        maroon: {
                            50:  '#fdf2f4',
                            100: '#fce7ea',
                            200: '#f9d0d6',
                            600: '#a0253b',
                            700: '#7c1c2e',
                            800: '#5e1522',
                            900: '#420f18',
                        },
                        gold: {
                            400: '#f5c842',
                            500: '#e8b800',
                        }
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased">

    {{-- ── Sidebar ─────────────────────────────────────────────────────────── --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 w-72 bg-gradient-to-b from-maroon-900 to-maroon-800
                  flex flex-col shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-maroon-700/50">
            <div class="w-9 h-9 rounded-xl bg-gold-400 flex items-center justify-center flex-shrink-0 shadow">
                <svg class="w-5 h-5 text-maroon-900" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gold-400 uppercase tracking-widest leading-none">WMSU TAMS</p>
                <p class="text-sm font-semibold text-white mt-0.5">Teacher Portal</p>
            </div>
        </div>

        {{-- Teacher identity --}}
        <div class="px-6 py-4 border-b border-maroon-700/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-maroon-700 border-2 border-maroon-600 flex items-center
                            justify-center text-white text-sm font-bold uppercase flex-shrink-0">
                    {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-maroon-300 truncate">Subject Teacher</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            @php
                $navItems = [
                    ['route' => 'teacher.dashboard',       'label' => 'Overview',            'icon' => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z'],
                    ['route' => 'teacher.classes',         'label' => 'My Classes',           'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
                    ['route' => 'teacher.assignments',     'label' => 'Assignments',          'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z'],
                    ['route' => 'teacher.submissions.inbox','label' => 'Submissions Inbox',  'icon' => 'M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z'],
                    ['route' => 'teacher.reminders',       'label' => 'Reminders',           'icon' => 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0'],
                    ['route' => 'teacher.profile',         'label' => 'Profile & Settings',  'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                          {{ $isActive
                                ? 'bg-white/15 text-white shadow-sm'
                                : 'text-maroon-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-gold-400' : 'text-maroon-300' }}"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['label'] }}
                    @if($isActive)
                        <span class="ml-auto w-1.5 h-1.5 bg-gold-400 rounded-full"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- Sign Out --}}
        <div class="px-3 py-4 border-t border-maroon-700/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium
                               text-maroon-300 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main wrapper ──────────────────────────────────────────────────────── --}}
    <div class="lg:ml-72 flex flex-col min-h-screen">

        {{-- Top header --}}
        <header class="sticky top-0 z-30 h-16 bg-white/90 backdrop-blur border-b border-gray-100
                       flex items-center justify-between px-5 sm:px-8 shadow-sm">
            {{-- Mobile hamburger --}}
            <button id="menu-toggle"
                    class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200
                           text-gray-500 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            {{-- Page title slot --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400 hidden sm:block">Teacher</span>
                <span class="text-gray-300 hidden sm:block">/</span>
                <span class="text-sm font-bold text-gray-700">@yield('page_title', 'Overview')</span>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.submissions.inbox') }}"
                   class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200
                          text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                    </svg>
                </a>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-maroon-800 to-maroon-700
                            text-white text-xs font-bold flex items-center justify-center uppercase">
                    {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-5 sm:p-8">

            {{-- Flash alerts --}}
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

            @yield('teacher_content')
        </main>

        <footer class="border-t border-gray-100 px-8 py-4 text-xs text-gray-400">
            &copy; {{ date('Y') }} WMSU Task Assignment &amp; Monitoring System — Teacher Portal
        </footer>
    </div>

    <script>
        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar    = document.getElementById('sidebar');

        menuToggle && menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('-translate-x-full');
        });

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
    </script>
    @stack('scripts')
</body>
</html>
