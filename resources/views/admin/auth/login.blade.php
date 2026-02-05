@extends('layouts.guest')

@section('title', 'Login Admin | SIM Buku Wisuda')

@section('content')
<div class="min-h-screen flex flex-col bg-slate-50 relative overflow-hidden">
    <!-- Navbar -->
    <x-landing-nav />

    <!-- Content -->
    <div class="flex-1 flex items-center justify-center p-4 relative pt-20">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uin-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-uin-gold/10 rounded-full blur-3xl"></div>

        <div class="w-full max-w-[400px] bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-gray-100/50 relative z-10">
            <div class="p-8 pb-6 text-center">
                 <div class="w-16 h-16 bg-gradient-to-br from-uin-primary to-uin-dark rounded-xl mx-auto flex items-center justify-center text-white font-serif font-bold text-3xl mb-4 shadow-lg shadow-emerald-700/20">
                    U
                 </div>
                 <h2 class="text-xl font-bold text-slate-800">Admin Portal</h2>
                 <p class="text-slate-500 text-sm mt-1">Masuk untuk mengelola data.</p>
            </div>

            <div class="px-8 pb-8">
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-100 text-red-600 rounded-lg p-3 text-xs font-medium flex gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4" x-data="{ showPassword: false }">
                    @csrf
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-uin-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" required 
                                   class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-uin-primary/20 focus:border-uin-primary transition-all text-sm outline-none placeholder:text-slate-400"
                                   placeholder="admin@uin.ac.id">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-uin-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required 
                                   class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-uin-primary/20 focus:border-uin-primary transition-all text-sm outline-none placeholder:text-slate-400"
                                   placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none">
                                <svg x-show="!showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" style="display: none;" class="h-5 w-5 text-slate-400 hover:text-slate-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.591-2.772m4.349-4.349a10.05 10.05 0 018.102 7c-.642 2.22-2.131 4.14-4.102 5.375M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 0l-6-6m12 12l-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-uin-primary border-gray-300 rounded focus:ring-uin-primary">
                            <span class="ml-2 text-xs text-slate-500 font-medium">Ingat saya</span>
                        </label>
                        <a href="#" class="text-xs text-uin-primary hover:text-uin-dark font-medium transition-colors">Lupa Password?</a>
                    </div>

                    <button type="submit" class="w-full bg-uin-primary hover:bg-uin-dark text-white font-bold py-3 rounded-lg transition-all shadow-lg shadow-emerald-700/20 hover:shadow-emerald-700/30 transform hover:-translate-y-0.5 active:translate-y-0">
                        Masuk
                    </button>
                </form>
            </div>
            
            <div class="bg-gray-50/80 rounded-b-2xl px-8 py-4 text-center border-t border-gray-100">
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">&copy; {{ date('Y') }} UIN Ar-Raniry Banda Aceh</p>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <x-footer />
</div>
@endsection
