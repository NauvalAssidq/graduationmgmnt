@extends('layouts.guest')

@section('title', $book->nama_buku . ' | SIM Buku Wisuda UIN Ar-Raniry')

@section('content')
    <x-landing-nav />

    <div class="pt-28 pb-12 bg-slate-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12 items-center lg:items-start">
                
                <!-- Book Cover Preview -->
                <div class="w-full max-w-[300px] flex-shrink-0">
                    <div class="aspect-[1/1.414] bg-white rounded-r-xl shadow-2xl relative overflow-hidden transform transition-transform hover:scale-[1.02] duration-300">
                        @if($book->template && $book->template->cover_html)
                            <div class="w-full h-full relative">
                                <iframe 
                                    srcdoc="<!DOCTYPE html><html><head><link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'><style>body{margin:0;background:white;overflow:hidden;width:210mm;height:297mm;} .a4-page{width:210mm;min-height:297mm;background:white;position:relative;overflow:hidden;} {{ $book->template->custom_css }}</style><script>function resize(){const w=document.body.clientWidth; const s=w/793.7; document.body.style.transform='scale('+s+')'; document.body.style.transformOrigin='0 0';} window.addEventListener('resize', resize); window.addEventListener('DOMContentLoaded', resize);</script></head><body>{{ $book->template->cover_html }}</body></html>"
                                    class="w-full h-full border-0 pointer-events-none bg-white"
                                    style="transform: scale(0.38); transform-origin: 0 0; width: 265%; height: 265%;" 
                                    scrolling="no"
                                ></iframe>
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-tr from-black/10 to-transparent pointer-events-none mix-blend-multiply"></div>
                                <div class="absolute left-0 top-0 h-full w-4 bg-gradient-to-r from-black/20 to-transparent mix-blend-multiply pointer-events-none"></div>
                            </div>
                        @else
                            <!-- Fallback Generic Cover -->
                            <div class="w-full h-full bg-uin-primary flex flex-col items-center justify-center p-8 text-white relative">
                                <div class="absolute left-0 top-0 h-full w-4 bg-black/20"></div>
                                <div class="w-24 h-24 mb-6 opacity-90"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 7l11 5 9-5-9-5z"/></svg></div>
                                <h2 class="font-serif font-bold text-2xl text-center leading-tight mb-2">{{ $book->nama_buku }}</h2>
                                <p class="uppercase tracking-widest text-sm opacity-80">{{ $book->gelombang }} {{ $book->tahun }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Book Details -->
                <div class="flex-1 text-center lg:text-left space-y-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-uin-primary/20 text-uin-primary text-xs font-semibold uppercase tracking-wider mb-4 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-uin-gold animate-pulse"></span>
                            Official Archive
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-slate-900 leading-tight mb-4">
                            {{ $book->nama_buku }}
                        </h1>
                        <p class="text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0">
                            Buku Wisuda resmi untuk {{ $book->gelombang }} Tahun Akademik {{ $book->tahun }}. 
                            Terbit pada {{ \Carbon\Carbon::parse($book->tanggal_terbit)->translatedFormat('d F Y') }}.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <div class="bg-white px-6 py-3 rounded-lg border border-gray-200 shadow-sm">
                            <span class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Total Wisudawan</span>
                            <span class="block text-2xl font-serif font-bold text-slate-800">{{ $graduates->total() }}</span>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-lg border border-gray-200 shadow-sm">
                            <span class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Tahun</span>
                            <span class="block text-2xl font-serif font-bold text-slate-800">{{ $book->tahun }}</span>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-center lg:justify-start gap-4">
                        @if($book->file_pdf)
                            <a href="{{ route('buku.flipbook', $book->slug) }}" target="_blank" class="inline-flex items-center gap-2 bg-white text-uin-primary border border-uin-primary hover:bg-slate-50 px-6 py-3 rounded-lg font-medium transition-colors shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Preview
                            </a>
                            <a href="{{ asset('storage/' . $book->file_pdf) }}" download class="inline-flex items-center gap-2 bg-uin-primary hover:bg-uin-dark text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download PDF
                            </a>
                        @else
                            <button disabled class="inline-flex items-center gap-2 bg-slate-100 text-slate-400 px-6 py-3 rounded-lg font-medium cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                PDF Belum Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graduates List -->
    <div class="py-16 bg-white min-h-[500px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-8 pb-4 border-b border-gray-100">Daftar Wisudawan</h2>

            @if($graduates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($graduates as $grad)
                        <div class="group bg-white rounded-xl border border-gray-100 p-4 hover:border-uin-primary/30 hover:shadow-lg transition-all duration-300 flex items-start gap-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex-shrink-0 overflow-hidden border border-gray-100 group-hover:border-uin-primary/20 transition-colors">
                                @if($grad->foto)
                                    <img src="{{ asset('storage/'.$grad->foto) }}" alt="{{ $grad->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-900 text-base truncate group-hover:text-uin-primary transition-colors">{{ $grad->nama }}</h3>
                                <p class="text-sm text-uin-primary font-medium mb-0.5 truncate">{{ $grad->nim }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $grad->prodi }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $graduates->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-slate-500">Belum ada data wisudawan untuk buku ini.</p>
                </div>
            @endif
        </div>
    </div>

    <x-footer />
@endsection
