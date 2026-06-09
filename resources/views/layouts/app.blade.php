<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'TAM System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
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
</head>
<body class="font-sans antialiased">
    @yield('content')
</body>
</html>
