<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM Buku Wisuda | UIN Ar-Raniry')</title>
    
    <!-- Meta Tags -->
    @stack('meta')

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        uin: {
                            primary: '#047857', // Emerald 700
                            dark: '#064e3b',    // Emerald 900
                            light: '#10b981',   // Emerald 500
                            gold: '#fbbf24',    // Amber 400
                            goldDark: '#d97706', // Amber 600
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    @yield('content')

    @stack('scripts')
</body>
</html>
