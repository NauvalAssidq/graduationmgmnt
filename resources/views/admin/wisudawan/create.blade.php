@extends('layouts.dashboard')

@section('header', 'Tambah Wisudawan')

@section('content')
        <x-breadcrumb :items="['Kelola Wisudawan' => route('wisudawan.index'), 'Tambah Data' => null]" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Tambah Data Wisudawan</h1>
            <p class="text-slate-500 text-sm mt-1">Lengkapi formulir di bawah ini untuk menambahkan data wisudawan baru.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-6">
            <form action="{{ route('wisudawan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Personal Info --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Informasi Pribadi</h3>
                        
                        <div>
                            <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nama') }}" required>
                            @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="nim" class="block text-sm font-medium text-slate-700 mb-1">NIM</label>
                            <input type="text" name="nim" id="nim" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nim') }}" required>
                            @error('nim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="ttl" class="block text-sm font-medium text-slate-700 mb-1">Tempat, Tgl Lahir</label>
                                <input type="text" name="ttl" id="ttl" placeholder="Banda Aceh, 10-10-2000" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('ttl') }}" required>
                                @error('ttl') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                                <x-select 
                                    name="jenis_kelamin" 
                                    :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" 
                                    :value="old('jenis_kelamin')" 
                                    placeholder="Pilih Gender" 
                                />
                                @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="foto" class="block text-sm font-medium text-slate-700 mb-1">Foto Wisudawan</label>
                            <input type="file" name="foto" id="foto" accept="image/*" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" required>
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Max: 2MB.</p>
                            @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Academic Info --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Informasi Akademik</h3>

                        <div>
                            <label for="id_buku" class="block text-sm font-medium text-slate-700 mb-1">Pilih Buku Wisuda</label>
                            <x-select 
                                name="id_buku" 
                                :options="$books->mapWithKeys(fn($b) => [$b->id => $b->nama_buku . ' (' . $b->tahun . ')'])" 
                                :value="old('id_buku')" 
                                placeholder="-- Pilih Buku --" 
                            />
                            @error('id_buku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fakultas" class="block text-sm font-medium text-slate-700 mb-1">Fakultas</label>
                            <input type="text" name="fakultas" id="fakultas" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('fakultas') }}" required>
                            @error('fakultas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="prodi" class="block text-sm font-medium text-slate-700 mb-1">Program Studi</label>
                            <input type="text" name="prodi" id="prodi" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('prodi') }}" required>
                            @error('prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="nomor" class="block text-sm font-medium text-slate-700 mb-1">Nomor Ijazah</label>
                                <input type="text" name="nomor" id="nomor" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nomor') }}" required>
                                @error('nomor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="ipk" class="block text-sm font-medium text-slate-700 mb-1">IPK</label>
                                <input type="number" step="0.01" min="0" max="4.00" name="ipk" id="ipk" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('ipk') }}" required>
                                @error('ipk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="ka_yudisium" class="block text-sm font-medium text-slate-700 mb-1">Predikat Yudisium</label>
                            <input type="text" name="ka_yudisium" id="ka_yudisium" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('ka_yudisium') }}" placeholder="Contoh: Pujian / Cumlaude" required>
                            @error('ka_yudisium') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="judul_thesis" class="block text-sm font-medium text-slate-700 mb-1">Judul Skripsi/Tesis</label>
                            <textarea name="judul_thesis" id="judul_thesis" rows="3" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>{{ old('judul_thesis') }}</textarea>
                            @error('judul_thesis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('wisudawan.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 text-sm font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Simpan Data</button>
                </div>
            </form>
    </div>
@endsection
