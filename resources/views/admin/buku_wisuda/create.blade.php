@extends('layouts.dashboard')

@section('header', 'Tambah Buku Wisuda')

@section('content')
        <x-breadcrumb :items="['Kelola Buku' => route('buku-wisuda.index'), 'Tambah Buku' => null]" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Tambah Buku Wisuda</h1>
            <p class="text-slate-500 text-sm mt-1">Buat periode buku wisuda baru.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-6">
            <form action="{{ route('buku-wisuda.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_buku" class="block text-sm font-medium text-slate-700 mb-1">Nama Buku</label>
                        <input type="text" name="nama_buku" id="nama_buku" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nama_buku') }}" placeholder="Contoh: Buku Wisuda Angkatan 65" required>
                        @error('nama_buku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="template_id" class="block text-sm font-medium text-slate-700 mb-1">Template Layout (Optional)</label>
                        <select name="template_id" id="template_id" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                            <option value="">-- Pilih Template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->nama }}" {{ old('template_id') == $template->nama ? 'selected' : '' }}>{{ $template->nama }} ({{ $template->layout }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Pilih layout untuk cetak PDF otomatis.</p>
                        @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                        <select name="status" id="status" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft (Belum Tampil)</option>
                            <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Published (Tampil di Web)</option>
                            <option value="Archived" {{ old('status') == 'Archived' ? 'selected' : '' }}>Archived (Arsip)</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Hanya buku "Published" yang muncul di halaman depan.</p>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gelombang" class="block text-sm font-medium text-slate-700 mb-1">Gelombang / Angkatan</label>
                        <input type="text" name="gelombang" id="gelombang" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('gelombang') }}" placeholder="Contoh: Gelombang I" required>
                        @error('gelombang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-slate-700 mb-1">Tahun Akademik</label>
                        <input type="number" name="tahun" id="tahun" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y')+1 }}" required>
                        @error('tahun') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_terbit" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Terbit / Wisuda</label>
                        <input type="date" name="tanggal_terbit" id="tanggal_terbit" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tanggal_terbit') }}" required>
                        @error('tanggal_terbit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="file_pdf" class="block text-sm font-medium text-slate-700 mb-1">File Buku (PDF)</label>
                        <input type="file" name="file_pdf" id="file_pdf" accept="application/pdf" class="w-full p-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-300 rounded-lg">
                        <p class="text-xs text-slate-400 mt-1">Opsional. Format: PDF. Max: 20MB.</p>
                        @error('file_pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('buku-wisuda.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 text-sm font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Simpan Buku</button>
                </div>
            </form>
    </div>
@endsection
