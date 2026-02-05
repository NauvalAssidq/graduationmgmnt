@extends('layouts.dashboard')

@section('header', 'Edit Buku Wisuda')

@section('content')
        <x-breadcrumb :items="['Kelola Buku' => route('buku-wisuda.index'), 'Edit Buku' => null]" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Edit Buku Wisuda</h1>
            <p class="text-slate-500 text-sm mt-1">Perbarui informasi buku wisuda.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-6">
            <form action="{{ route('buku-wisuda.update', $bukuWisuda) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_buku" class="block text-sm font-medium text-slate-700 mb-1">Nama Buku</label>
                        <input type="text" name="nama_buku" id="nama_buku" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nama_buku', $bukuWisuda->nama_buku) }}" required>
                        @error('nama_buku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="template_id" class="block text-sm font-medium text-slate-700 mb-1">Template Layout (Optional)</label>
                        <select name="template_id" id="template_id" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                            <option value="">-- Pilih Template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->nama }}" {{ old('template_id', $bukuWisuda->template_id) == $template->nama ? 'selected' : '' }}>{{ $template->nama }} ({{ $template->layout }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Pilih layout untuk cetak PDF otomatis.</p>
                        @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                        <select name="status" id="status" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            <option value="Draft" {{ old('status', $bukuWisuda->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Published" {{ old('status', $bukuWisuda->status) == 'Published' ? 'selected' : '' }}>Published</option>
                            <option value="Archived" {{ old('status', $bukuWisuda->status) == 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gelombang" class="block text-sm font-medium text-slate-700 mb-1">Gelombang</label>
                        <input type="text" name="gelombang" id="gelombang" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('gelombang', $bukuWisuda->gelombang) }}" required>
                        @error('gelombang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tahun', $bukuWisuda->tahun) }}" required>
                        @error('tahun') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_terbit" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" id="tanggal_terbit" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tanggal_terbit', $bukuWisuda->tanggal_terbit) }}" required>
                        @error('tanggal_terbit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="file_pdf" class="block text-sm font-medium text-slate-700 mb-1">File PDF</label>
                        @if($bukuWisuda->file_pdf)
                            <div class="flex items-center gap-2 mb-2 p-2 bg-slate-50 rounded border border-gray-200">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                <a href="{{ Storage::url($bukuWisuda->file_pdf) }}" target="_blank" class="text-xs text-blue-600 hover:underline truncate">{{ basename($bukuWisuda->file_pdf) }}</a>
                            </div>
                        @endif
                        <input type="file" name="file_pdf" id="file_pdf" accept="application/pdf" class="w-full p-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-300 rounded-lg">
                        <p class="text-xs text-slate-400 mt-1">Upload baru untuk mengganti file saat ini. Max: 20MB.</p>
                        @error('file_pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('buku-wisuda.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 text-sm font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Perbarui Buku</button>
                </div>
            </form>
    </div>
@endsection
