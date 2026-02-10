@extends('layouts.dashboard')

@section('header', 'Arsip Buku Wisuda')

@section('content')
        <x-breadcrumb :items="['Arsip Buku' => route('admin.arsip.index')]" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Arsip Buku Wisuda</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar buku wisuda yang telah dipublikasikan dan siap cetak.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50">
                <form action="{{ route('admin.arsip.index') }}" method="GET" class="w-full sm:w-96 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="pl-10 p-2 w-full rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Cari arsip buku...">
                    @if(request('sort_by'))
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold">
                                <a href="{{ route('admin.arsip.index', ['sort_by' => 'nama_buku', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group flex items-center gap-1 hover:text-emerald-700">
                                    Nama Buku
                                    @if(request('sort_by') == 'nama_buku')
                                        <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">
                                <a href="{{ route('admin.arsip.index', ['sort_by' => 'gelombang', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group inline-flex items-center gap-1 hover:text-emerald-700">
                                    Periode
                                    @if(request('sort_by') == 'gelombang')
                                        <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">Jumlah Wisudawan</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">
                                <a href="{{ route('admin.arsip.index', ['sort_by' => 'status', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group inline-flex items-center gap-1 hover:text-emerald-700">
                                    Status
                                    @if(request('sort_by') == 'status')
                                        <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($archives as $book)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $book->nama_buku }}</div>
                                    <div class="text-xs text-slate-500 mt-1">Terbit: {{ \Carbon\Carbon::parse($book->tanggal_terbit)->translatedFormat('d F Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-slate-900 font-medium">{{ $book->gelombang }}</div>
                                    <div class="text-xs text-slate-500">{{ $book->tahun }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $book->wisudawan->count() }} Alumni
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        {{ $book->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <!-- Preview Button (opens in new tab for browser print) -->
                                    <a href="{{ route('admin.arsip.preview', $book->id) }}" target="_blank" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors shadow-sm mr-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Preview
                                    </a>
                                    <form action="{{ route('admin.arsip.generate', $book->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @if($book->file_pdf)
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors shadow-sm" onclick="return confirm('Anda yakin ingin menghapus PDF buku ini?')">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus PDF
                                            </button>
                                        @else
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium rounded-md transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                Generate PDF
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <p class="font-medium text-slate-600">Belum ada arsip buku</p>
                                        <p class="text-xs text-slate-400 mt-1">Pastikan buku sudah berstatus "Published" untuk muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>
@endsection
