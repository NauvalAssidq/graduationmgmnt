@extends('layouts.guest')

@section('title', 'Hasil Pencarian | SIM Buku Wisuda UIN Ar-Raniry')

@section('content')
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-uin-primary rounded-lg flex items-center justify-center text-white font-serif font-bold text-xl">U</div>
                    <div>
                        <h1 class="font-bold text-slate-900 leading-tight">SIM Buku Wisuda</h1>
                        <p class="text-[10px] tracking-widest uppercase text-uin-primary font-semibold">UIN Ar-Raniry</p>
                    </div>
                </a>
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-uin-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-serif font-bold text-slate-900">Hasil Pencarian</h1>
                <p class="text-slate-500">Menampilkan hasil untuk Query: <span class="font-semibold text-slate-800">"{{ $query }}"</span></p>
            </div>

            @if(count($graduates) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($graduates as $grad)
                        <div class="bg-white rounded-xl border border-gray-300 p-6 hover:shadow-lg transition-all duration-300 flex items-start gap-4">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex-shrink-0 overflow-hidden">
                                @if($grad->foto)
                                    <img src="{{ asset('storage/'.$grad->foto) }}" alt="{{ $grad->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">{{ $grad->nama }}</h3>
                                <p class="text-sm text-uin-primary font-medium mb-1">{{ $grad->nim }}</p>
                                <p class="text-xs text-slate-500 mb-2">{{ $grad->prodi }} - {{ $grad->fakultas }}</p>
                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $grad->bukuWisuda->gelombang ?? 'Gelombang ?' }} / {{ $grad->bukuWisuda->tahun ?? '?' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ditemukan</h3>
                    <p class="text-slate-500 text-sm">Maaf, kami tidak menemukan alumni dengan nama atau NIM tersebut.</p>
                    <a href="{{ route('home') }}" class="mt-6 text-uin-primary hover:text-uin-dark font-medium text-sm">Coba pencarian lain</a>
                </div>
            @endif
        </div>
    </main>
    
    <x-footer />
@endsection
