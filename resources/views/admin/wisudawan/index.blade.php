@extends('layouts.dashboard')

@section('header', 'Kelola Wisudawan')

@section('content')
        <x-breadcrumb :items="['Kelola Wisudawan' => route('wisudawan.index')]" />

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-slate-800">Daftar Wisudawan</h1>
            <a href="{{ route('wisudawan.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Wisudawan
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300">
            <div class="p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                <form action="{{ route('wisudawan.index') }}" method="GET" class="flex flex-col gap-4">
                    <!-- Top Row: Search & Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                         <!-- Search -->
                        <div class="relative">
                            <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 hover:text-emerald-600 transition-colors cursor-pointer" title="Cari">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                            <input type="text" name="search" value="{{ request('search') }}" class="pl-10 p-2 w-full rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-400" placeholder="Cari nama atau NIM..." autocomplete="off">
                        </div>

                         <!-- Fakultas Filter -->
                        <div class="w-full">
                            <x-select 
                                name="fakultas" 
                                :options="$faculties" 
                                :value="request('fakultas')"
                                placeholder="Semua Fakultas"
                                class="w-full"
                                :submitOnChange="true"
                            />
                        </div>

                         <!-- Prodi Filter (Searchable) -->
                        <div class="w-full">
                            <x-select 
                                name="prodi" 
                                :options="$prodis" 
                                :value="request('prodi')"
                                placeholder="Semua Prodi"
                                :searchable="true"
                                class="w-full"
                                :submitOnChange="true"
                            />
                        </div>
                        
                        <!-- Yudisium Filter -->
                        <div class="w-full">
                            <x-select 
                                name="yudisium" 
                                :options="$predikats" 
                                :value="request('yudisium')"
                                placeholder="Semua Predikat"
                                class="w-full"
                                :submitOnChange="true"
                            />
                        </div>
                    </div>

                    <!-- Hidden Sort Params to persist sort when filtering -->
                    @if(request('sort_by'))
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                    @endif
                    
                    <div class="flex justify-end">
                         <a href="{{ route('wisudawan.index') }}" class="text-sm text-slate-500 hover:text-emerald-600 underline">Reset Filter</a>
                    </div>
                </form>
            </div>
            
            <div class="overflow-x-auto rounded-b-xl">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold">
                                <a href="{{ route('wisudawan.index', array_merge(request()->query(), ['sort_by' => 'nim', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-emerald-700 cursor-pointer">
                                    NIM
                                    @if(request('sort_by') == 'nim')
                                        <span class="text-emerald-600 font-bold text-lg leading-none">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @else
                                        <span class="text-gray-300 text-lg leading-none">↕</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold">
                                <a href="{{ route('wisudawan.index', array_merge(request()->query(), ['sort_by' => 'nama', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-emerald-700 cursor-pointer">
                                    Nama Lengkap
                                    @if(request('sort_by') == 'nama')
                                        <span class="text-emerald-600 font-bold text-lg leading-none">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @else
                                        <span class="text-gray-300 text-lg leading-none">↕</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold">
                                <a href="{{ route('wisudawan.index', array_merge(request()->query(), ['sort_by' => 'prodi', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-emerald-700 cursor-pointer">
                                    Prodi
                                    @if(request('sort_by') == 'prodi')
                                        <span class="text-emerald-600 font-bold text-lg leading-none">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @else
                                        <span class="text-gray-300 text-lg leading-none">↕</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">
                                <a href="{{ route('wisudawan.index', array_merge(request()->query(), ['sort_by' => 'ipk', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex items-center gap-1 hover:text-emerald-700 cursor-pointer">
                                    IPK
                                    @if(request('sort_by') == 'ipk')
                                        <span class="text-emerald-600 font-bold text-lg leading-none">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                    @else
                                        <span class="text-gray-300 text-lg leading-none">↕</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold">Buku Wisuda</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($graduates as $graduate)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-slate-600">{{ $graduate->nim }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs shrink-0 overflow-hidden">
                                        @if($graduate->foto)
                                            <img src="{{ Storage::url($graduate->foto) }}" alt="{{ $graduate->nama }}" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($graduate->nama, 0, 1) }}
                                        @endif
                                    </div>
                                    {{ $graduate->nama }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $graduate->prodi }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $graduate->ipk >= 3.5 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ number_format($graduate->ipk, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    @if($graduate->bukuWisuda)
                                        {{ $graduate->bukuWisuda->nama_buku }}
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada buku</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('wisudawan.edit', $graduate) }}" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('wisudawan.destroy', $graduate) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data wisudawan ini?');">
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
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="font-medium text-slate-600">Belum ada data wisudawan</p>
                                        <p class="text-xs text-slate-400 mt-1">Silakan tambah data baru atau import dari CSV.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $graduates->onEachSide(1)->links() }}
            </div>
    </div>
@endsection
