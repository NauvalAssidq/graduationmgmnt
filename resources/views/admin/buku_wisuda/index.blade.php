@extends('layouts.dashboard')

@section('header', 'Kelola Buku Wisuda')

@section('content')
        <x-breadcrumb :items="['Kelola Buku' => route('buku-wisuda.index')]" />

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Daftar Buku Wisuda</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola data buku wisuda, periode, dan file digital.</p>
            </div>
            <a href="{{ route('buku-wisuda.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Buku
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
            {{-- Filter Section --}}
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="{{ route('buku-wisuda.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    {{-- Search --}}
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="pl-10 w-full p-2.5 rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Cari buku atau gelombang...">
                    </div>
                    
                    {{-- Year Filter --}}
                    <div class="w-full sm:w-40">
                        <select name="tahun" class="w-full p-2.5 rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 text-slate-600 bg-white" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div class="w-full sm:w-40">
                        <select name="status" class="w-full p-2.5 rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 text-slate-600 bg-white" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

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
                                <a href="{{ route('buku-wisuda.index', ['sort_by' => 'nama_buku', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group flex items-center gap-1 hover:text-emerald-700">
                                    Nama Buku
                                    @if(request('sort_by') == 'nama_buku')
                                        <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">
                                <a href="{{ route('buku-wisuda.index', ['sort_by' => 'tahun', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group inline-flex items-center gap-1 hover:text-emerald-700">
                                    Periode
                                    @if(request('sort_by') == 'tahun')
                                        <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">Jumlah Wisudawan</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">
                                <a href="{{ route('buku-wisuda.index', ['sort_by' => 'status', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group inline-flex items-center gap-1 hover:text-emerald-700">
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
                        @forelse($books as $book)
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
                                        {{ $book->wisudawan_count }} Alumni
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match($book->status) {
                                            'Published' => 'bg-emerald-100 text-emerald-700',
                                            'Draft' => 'bg-amber-100 text-amber-700',
                                            'Archived' => 'bg-slate-100 text-slate-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $book->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($book->file_pdf)
                                            <a href="{{ Storage::url($book->file_pdf) }}" target="_blank" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Lihat PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('buku-wisuda.edit', $book) }}" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('buku-wisuda.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini? Semua data wisudawan terkait akan kehilangan referensi buku.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        <p class="font-medium text-slate-600">Belum ada buku wisuda</p>
                                        <p class="text-xs text-slate-400 mt-1">Buat buku baru untuk memulai penerimaan data wisudawan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $books->onEachSide(1)->links() }}
            </div>
    </div>
@endsection
