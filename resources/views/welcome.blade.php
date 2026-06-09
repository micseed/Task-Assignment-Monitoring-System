@php
    $user = auth()->user();
    if ($user && $user->isAdmin()) {
        header('Location: ' . route('admin.dashboard'));
        exit();
    }
    if ($user && $user->isTeacher()) {
        header('Location: ' . route('teacher.dashboard'));
        exit();
    }
    if ($user && $user->isStudent()) {
        header('Location: ' . route('student.dashboard'));
        exit();
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome — TAM System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        maroon: { 700: '#7c1c2e', 800: '#5e1522', 900: '#420f18' },
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-50 font-sans">

{{-- Top bar --}}
<header class="h-14 bg-gray-900 flex items-center justify-between px-6 sm:px-10">
    <span class="text-sm font-bold text-white tracking-tight">WMSU TAM System</span>
    <div class="flex items-center gap-4">
        <span class="text-xs text-gray-400">
            Logged in as <strong class="text-white font-semibold">{{ $user->name }}</strong>
        </span>
        <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 border border-gray-700 rounded px-2 py-0.5">
            {{ ucfirst($user->role) }}
        </span>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit"
                    class="text-xs text-gray-300 border border-gray-600 rounded px-3 py-1.5 hover:bg-gray-800 hover:border-gray-500 transition-colors">
                Sign Out
            </button>
        </form>
    </div>
</header>

{{-- Main --}}
<main class="flex items-center justify-center min-h-[calc(100vh-56px)] px-4 py-12">
    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Top colored stripe --}}
        <div class="h-1.5 bg-gradient-to-r from-maroon-800 to-maroon-700"></div>

        <div class="px-8 py-8">
            @if($user->isStudent())
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Student</p>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                    Welcome, <span class="font-light text-gray-500">{{ $user->first_name }}</span>
                </h2>
                <div class="w-8 h-1 bg-maroon-800 rounded mt-4 mb-5"></div>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">
                    Track your assignments, submit your work, view grades and teacher feedback, and stay on top of every deadline.
                </p>
                <ul class="divide-y divide-gray-100 mb-6">
                    <li class="flex items-center justify-between py-3 text-sm text-gray-600">My Assignments &amp; Deadlines <span class="text-[11px] text-gray-400 border border-gray-200 rounded px-2 py-0.5">Coming soon</span></li>
                    <li class="flex items-center justify-between py-3 text-sm text-gray-600">Submit PDF or Code <span class="text-[11px] text-gray-400 border border-gray-200 rounded px-2 py-0.5">Coming soon</span></li>
                    <li class="flex items-center justify-between py-3 text-sm text-gray-600">Unsubmit &amp; Resubmit Before Deadline <span class="text-[11px] text-gray-400 border border-gray-200 rounded px-2 py-0.5">Coming soon</span></li>
                    <li class="flex items-center justify-between py-3 text-sm text-gray-600">Grades &amp; Feedback <span class="text-[11px] text-gray-400 border border-gray-200 rounded px-2 py-0.5">Coming soon</span></li>
                </ul>
            @endif

            <div class="text-center text-xs text-gray-400 bg-gray-50 border border-gray-100 rounded-xl py-3">
                Student portal coming soon &mdash; stay tuned.
            </div>
        </div>
    </div>
</main>

<footer class="text-center py-5 text-xs text-gray-400 border-t border-gray-100">
    &copy; {{ date('Y') }} Task Assignment &amp; Monitoring System — WMSU
</footer>

</body>
</html>
