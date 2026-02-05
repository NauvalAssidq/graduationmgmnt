<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 cursor-pointer focus:outline-none">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-medium text-slate-700">{{ Auth::user()->name ?? 'Administrator' }}</p>
            <p class="text-xs text-slate-500">Admin</p>
        </div>
        <div class="h-9 w-9 bg-emerald-100 border border-emerald-200 rounded-full flex items-center justify-center text-emerald-700 font-semibold text-sm">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
        <svg class="w-4 h-4 text-slate-400" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
         class="absolute right-0 mt-3 w-60 bg-white rounded-md shadow-lg border border-gray-100 z-50 ring-1 ring-black ring-opacity-5"
         style="display: none;">
        
        <div class="px-5 py-2 border-b border-gray-100 bg-gray-50/50">
            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Signed in as</p>
            <p class="text-sm font-semibold text-slate-800 truncate mt-0.5">{{ Auth::user()->email ?? 'admin@ar-raniry.ac.id' }}</p>
        </div>

        <div class="">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-5 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Lihat Website
            </a>
            
            <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Edit Profil
            </a>
        </div>
        
        <div class="border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-3 px-5 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors group font-medium">
                    <svg class="w-4 h-4 text-red-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
