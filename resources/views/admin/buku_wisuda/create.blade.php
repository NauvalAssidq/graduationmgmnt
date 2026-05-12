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
                        <x-select 
                            name="template_id" 
                            :options="$templates->mapWithKeys(function ($t) { return [$t->template_id => $t->nama . ' (' . $t->layout . ')']; })->toArray()" 
                            :value="old('template_id')" 
                            placeholder="-- Pilih Template --"
                            class="w-full" 
                        />
                        <p class="text-xs text-slate-400 mt-1">Pilih layout untuk cetak PDF otomatis.</p>
                        @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                        <x-select 
                            name="status" 
                            :options="['Draft' => 'Draft (Belum Tampil)', 'Published' => 'Published (Tampil di Web)', 'Archived' => 'Archived (Arsip)']" 
                            :value="old('status', 'Draft')" 
                            class="w-full" 
                        />
                        <p class="text-xs text-slate-400 mt-1">Hanya buku "Published" yang muncul di halaman depan.</p>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gelombang" class="block text-sm font-medium text-slate-700 mb-1">Gelombang / Angkatan</label>
                        <input type="text" name="gelombang" id="gelombang" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('gelombang') }}" placeholder="Contoh: Gelombang I" required>
                        @error('gelombang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-slate-700 mb-1">Tahun (Misal: 2026)</label>
                        <input type="number" name="tahun" id="tahun" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y')+1 }}" required>
                        @error('tahun') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tahun_akademik" class="block text-sm font-medium text-slate-700 mb-1">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tahun_akademik') }}" placeholder="Contoh: 2025/2026">
                        @error('tahun_akademik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <hr class="my-2 border-gray-200">
                        <h3 class="text-sm font-semibold text-slate-700 mb-2">Informasi SK & Rektor</h3>
                    </div>

                    <div>
                        <label for="nomor_sk" class="block text-sm font-medium text-slate-700 mb-1">Nomor SK</label>
                        <input type="text" name="nomor_sk" id="nomor_sk" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nomor_sk') }}" placeholder="Contoh: B-087/Un.08/R/PP.00.9/03/2026">
                        @error('nomor_sk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_sk" class="block text-sm font-medium text-slate-700 mb-1">Tanggal SK / Penetapan</label>
                        <input type="date" name="tanggal_sk" id="tanggal_sk" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('tanggal_sk') }}">
                        @error('tanggal_sk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama_rektor" class="block text-sm font-medium text-slate-700 mb-1">Nama Rektor</label>
                        <input type="text" name="nama_rektor" id="nama_rektor" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nama_rektor') }}" placeholder="Contoh: Prof. Dr. H. Mujiburrahman, M.Ag">
                        @error('nama_rektor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nip_rektor" class="block text-sm font-medium text-slate-700 mb-1">NIP Rektor</label>
                        <input type="text" name="nip_rektor" id="nip_rektor" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nip_rektor') }}" placeholder="Contoh: 19710908 199903 1 004">
                        @error('nip_rektor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="sambutan_rektor" class="block text-sm font-medium text-slate-700 mb-1">Kata Pengantar / Sambutan Rektor</label>
                        <textarea name="sambutan_rektor" id="sambutan_rektor" rows="12" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="Masukkan isi sambutan rektor di sini.">{{ old('sambutan_rektor') }}</textarea>
                        @error('sambutan_rektor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#sambutan_rektor',
        plugins: 'advlist autolink lists link charmap preview searchreplace visualblocks code fullscreen insertdatetime table wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        menubar: false,
        branding: false,
        height: 300
    });
</script>
@endpush
