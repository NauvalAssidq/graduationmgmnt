@extends('layouts.dashboard')

@section('title', 'Kelola Admin')

@section('header', 'Kelola Admin')

@section('content')
<div class="space-y-6">
    <x-breadcrumb :items="['Kelola Admin' => route('kelola-admin.index')]" />

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h1 class="text-2xl font-bold text-slate-800">Daftar Administrator</h1>
        <a href="{{ route('kelola-admin.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Admin
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-300">

        {{-- Search Bar --}}
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
            <form action="{{ route('kelola-admin.index') }}" method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 hover:text-emerald-600 transition-colors cursor-pointer" title="Cari">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    <input type="text" name="search" value="{{ request('search') }}" class="pl-10 p-2 w-full rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-400" placeholder="Cari nama atau email..." autocomplete="off">
                </div>
                @if(request('search'))
                    <a href="{{ route('kelola-admin.index') }}" class="text-sm text-slate-500 hover:text-emerald-600 underline">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-b-xl">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold">#</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Nama</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Email</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Terdaftar</th>
                        <th scope="col" class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($admins as $admin)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ $admins->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs shrink-0">
                                        {{ substr($admin->name ?? $admin->email, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $admin->name ?? '—' }}</p>
                                        @if(Auth::id() === $admin->id)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700 mt-0.5">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $admin->email }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $admin->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kelola-admin.edit', $admin) }}" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    @if(Auth::id() !== $admin->id)
                                        <form action="{{ route('kelola-admin.destroy', $admin) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-1.5 text-slate-300 cursor-not-allowed rounded-md" title="Tidak dapat menghapus akun sendiri">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="font-medium text-slate-600">Belum ada data administrator</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik "Tambah Admin" untuk menambahkan administrator baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($admins->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $admins->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
