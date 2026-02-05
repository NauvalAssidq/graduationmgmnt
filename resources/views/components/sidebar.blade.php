<aside class="w-64 bg-emerald-800 border-r border-emerald-700 flex-shrink-0 fixed h-full z-10 hidden lg:block">
    <div class="h-16 flex items-center px-6 border-b border-emerald-700">
        <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center mr-3 text-emerald-800 font-bold">
            U
        </div>
        <div>
            <h1 class="font-bold text-sm tracking-tight text-white">SIM Buku Wisuda</h1>
            <p class="text-[10px] text-emerald-200 font-medium">UIN AR-RANIRY</p>
        </div>
    </div>

    <nav class="p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:text-white hover:bg-emerald-700' }} text-sm font-medium rounded-md group transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <div class="pt-4 pb-2">
            <p class="px-3 text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">Buku Wisuda</p>
        </div>

        <a href="{{ route('buku-wisuda.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('buku-wisuda.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:text-white hover:bg-emerald-700' }} text-sm font-medium rounded-md transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Kelola Buku
        </a>

        <a href="{{ route('admin.arsip.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.arsip.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:text-white hover:bg-emerald-700' }} text-sm font-medium rounded-md transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            Kelola Arsip
        </a>

        <a href="{{ route('template.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('template.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:text-white hover:bg-emerald-700' }} text-sm font-medium rounded-md transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Kelola Template
        </a>

        <div class="pt-4 pb-2">
            <p class="px-3 text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">Mahasiswa</p>
        </div>

        <a href="{{ route('wisudawan.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('wisudawan.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:text-white hover:bg-emerald-700' }} text-sm font-medium rounded-md transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Kelola Wisudawan
        </a>
    </nav>
</aside>
