<nav class="fixed w-full z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo UIN" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-slate-900 leading-tight">SIM Buku Wisuda</h1>
                    <p class="text-[10px] tracking-widest uppercase text-uin-primary font-semibold">UIN Ar-Raniry</p>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6">
                <a href="/" class="text-sm font-medium text-slate-600 hover:text-uin-primary transition-colors">Beranda</a>
                <a href="#books" class="text-sm font-medium text-slate-600 hover:text-uin-primary transition-colors">Daftar Buku</a>
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-uin-primary hover:bg-uin-dark border border-transparent text-white text-sm font-medium rounded-full transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-uin-primary hover:bg-uin-dark border border-transparent text-white text-sm font-medium rounded-full transition-all">
                        Login Admin
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-slate-500 hover:text-uin-primary focus:outline-none p-2 rounded-md">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div x-show="mobileMenuOpen" 
         class="md:hidden bg-white border-b border-gray-200 shadow-lg absolute w-full left-0 z-40">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="#books" @click="mobileMenuOpen = false" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-uin-primary hover:bg-slate-50">Daftar Buku</a>
            
            @auth
                <a href="{{ route('admin.dashboard') }}" class="block w-full text-center px-5 py-3 bg-uin-primary hover:bg-uin-dark text-white text-base font-medium rounded-lg mt-4">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 bg-uin-primary hover:bg-uin-dark text-white text-base font-medium rounded-lg mt-4">Login Admin</a>
            @endauth
        </div>
    </div>
</nav>
