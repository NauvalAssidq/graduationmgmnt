@extends('layouts.guest')

@section('title', 'SIM Buku Wisuda Book | UIN Ar-Raniry Banda Aceh')

@push('meta')
    <meta name="description" content="Arsip Digital Resmi Alumni & Wisudawan Universitas Islam Negeri Ar-Raniry Banda Aceh. Temukan data wisudawan dan buku wisuda digital secara lengkap dan mudah.">
    <meta name="keywords" content="SIM Buku Wisuda, Wisuda UIN Ar-Raniry, Buku Wisuda Digital, Alumni UIN Ar-Raniry, Arsip Wisuda, Banda Aceh">
    <meta name="author" content="UIN Ar-Raniry Banda Aceh">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="SIM Buku Wisuda Book | UIN Ar-Raniry Banda Aceh">
    <meta property="og:description" content="Arsip Digital Resmi Alumni & Wisudawan Universitas Islam Negeri Ar-Raniry Banda Aceh.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="SIM Buku Wisuda Book | UIN Ar-Raniry Banda Aceh">
    <meta property="twitter:description" content="Arsip Digital Resmi Alumni & Wisudawan Universitas Islam Negeri Ar-Raniry Banda Aceh.">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">
    <link rel="canonical" href="{{ url('/') }}">
@endpush

@section('content')
    <!-- Navbar -->
    <x-landing-nav />

    <!-- Hero Section -->
    <section id="search" class="relative pt-28 pb-16 lg:pt-48 lg:pb-32 overflow-hidden bg-white border-b border-gray-200">
        <!-- Background Blobs -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 lg:w-96 lg:h-96 bg-uin-gold/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 lg:w-80 lg:h-80 bg-uin-primary/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                
                <!-- Text Content -->
                <div class="space-y-6 lg:space-y-8 order-2 lg:order-1 text-center lg:text-left z-20">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-50 border border-uin-primary/20 text-uin-primary text-xs font-semibold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-uin-gold animate-pulse"></span>
                        Official Document Archive
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-6xl font-serif font-bold text-slate-900 leading-tight">
                        SIM Buku Wisuda Book <br>
                        <span class="text-xl sm:text-2xl lg:text-4xl block mt-2 text-uin-primary font-sans font-semibold">UIN Ar-Raniry Banda Aceh</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Arsip Digital Resmi Alumni & Wisudawan Universitas Islam Negeri Ar-Raniry. Mengabadikan pencapaian akademik dalam kenangan abadi.
                    </p>

                    <form action="{{ route('cari.alumni') }}" method="GET" class="relative group w-full max-w-md mx-auto lg:mx-0">
                        <div class="flex items-center w-full bg-white rounded-lg border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-slate-950 focus-within:ring-offset-2 focus-within:border-slate-800 transition-all duration-200 ease-in-out hover:border-slate-300">
                             <!-- Icon -->
                            <div class="pl-4 text-slate-500">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            
                            <!-- Input -->
                            <input type="text" name="q" placeholder="Cari nama, NIM, atau prodi..." 
                                   class="w-full px-3 py-3.5 bg-transparent border-none focus:ring-0 text-sm font-medium text-slate-900 placeholder:text-slate-500">
                            
                            <!-- Button (Integrated, Minimal) -->
                            <div class="pr-2">
                                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                                    <span>Cari</span>
                                    <svg class="w-3.5 h-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-slate-400 font-medium pl-1">
                            Contoh: <span class="text-slate-500 hover:text-slate-900 cursor-pointer transition-colors">2025</span>, <span class="text-slate-500 hover:text-slate-900 cursor-pointer transition-colors">Teknologi Informasi</span>
                        </p>
                    </form>
                </div>

                <!-- 3D Book Visual -->
                <div class="relative flex justify-center items-center h-[320px] sm:h-[450px] order-1 lg:order-2 z-10">
                    <x-book-3d :book="$latestBook" />
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Books Section -->
    <!-- Books List Section -->
    <section id="books" class="py-20 bg-slate-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-3xl font-serif font-bold text-slate-900 mb-2">Daftar Buku Wisuda</h2>
                    <p class="text-slate-500 text-sm">Arsip digital wisuda dari berbagai periode.</p>
                </div>
                
                <!-- Book Search Form -->
                <form action="{{ route('home') }}#books" method="GET" class="w-full md:w-auto">
                    <div class="relative">
                        <input type="text" name="search_book" value="{{ request('search_book') }}" placeholder="Cari buku atau tahun..." 
                               class="w-full md:w-64 pl-4 pr-10 py-2 rounded-lg border border-gray-200 focus:border-uin-primary focus:ring-1 focus:ring-uin-primary outline-none transition-all text-sm">
                        <button type="submit" class="absolute right-3 top-2.5 text-slate-400 hover:text-uin-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            @if(isset($books) && $books->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                @foreach($books as $book)
                    @php
                        $colors = ['bg-uin-primary', 'bg-uin-dark', 'bg-uin-light', 'bg-slate-700'];
                        $colorClass = $colors[$loop->index % 4];
                    @endphp
                    <a href="{{ route('buku.show', $book) }}" class="group bg-white rounded-2xl p-4 border border-gray-100 hover:border-uin-primary/30 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[3/4] bg-slate-50 rounded-xl mb-4 relative overflow-hidden flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                            <!-- Mini Book Icon / Preview -->
                            @if($book->template && $book->template->cover_html)
                                <div class="w-full h-full relative">
                                    <iframe 
                                        srcdoc="<!DOCTYPE html><html><head><link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'><style>body{margin:0;background:white;overflow:hidden;width:210mm;height:297mm;} .a4-page{width:210mm;min-height:297mm;background:white;position:relative;overflow:hidden;} {{ $book->template->custom_css }}</style><script>function resize(){const w=window.innerWidth;const s=w/793.7;document.body.style.transform='scale('+s+')';document.body.style.transformOrigin='0 0';}window.addEventListener('resize', resize);window.addEventListener('DOMContentLoaded', resize);</script></head><body>{{ $book->template->cover_html }}</body></html>"
                                        class="w-full h-full border-0 pointer-events-none bg-white"
                                        scrolling="no"
                                    ></iframe>
                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent pointer-events-none"></div>
                                </div>
                            @else
                                <div class="w-20 h-28 {{ $colorClass }} rounded-r shadow-md relative transform group-hover:-translate-y-2 group-hover:rotate-y-12 transition-all duration-500" style="perspective: 500px;">
                                    <div class="absolute left-0 top-0 w-2 h-full bg-black/20"></div>
                                    <div class="h-full flex flex-col justify-center items-center p-2">
                                         <div class="w-6 h-6 text-white/90"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 7l11 5 9-5-9-5z"/></svg></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-1 group-hover:text-uin-primary transition-colors line-clamp-1 title-font">{{ $book->nama_buku }}</h3>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-medium mb-3">{{ $book->gelombang }} • {{ $book->tahun }}</p>
                        <div class="flex items-center justify-between text-xs font-medium text-slate-400 pt-3 border-t border-gray-50">
                            <span>{{ $book->wisudawan_count ?? 0 }} Alumni</span>
                            <span class="text-uin-primary group-hover:underline">Buka Arsip &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $books->fragment('books')->links() }}
            </div>

            @else
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                     <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                     </div>
                     <h3 class="text-xl font-bold text-slate-700 mb-2">Buku Tidak Ditemukan</h3>
                     <p class="text-slate-500 max-w-sm mx-auto">Tidak ada buku wisuda yang cocok dengan kriteria pencarian Anda.</p>
                </div>
            @endif
        </div>
    </section>
    <x-footer />
@endsection