<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard | SIM Buku Wisuda')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-slate-800 antialiased">

    <div class="min-h-screen flex">

        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            
            <!-- Header Component -->
            <x-header :title="$__env->yieldContent('header', 'Dashboard')" />

            <div class="p-8">
                 @yield('content')
            </div>
            
            <footer class="mt-auto border-t border-gray-200 bg-white py-4 px-8">
                <p class="text-xs text-center text-slate-400">
                    &copy; {{ date('Y') }} Universitas Islam Negeri Ar-Raniry Banda Aceh.
                </p>
            </footer>

        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
