@extends('layouts.dashboard')

@section('title', 'Edit Admin')

@section('header', 'Kelola Admin')

@section('content')
<div class="space-y-6">
    <x-breadcrumb :items="['Kelola Admin' => route('kelola-admin.index'), 'Edit Admin' => null]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Edit Administrator</h1>
    </div>

    <form action="{{ route('kelola-admin.update', $admin) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-300 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-base font-semibold text-slate-800">Informasi Akun</h2>
                <p class="text-sm text-slate-500">Perbarui detail akun administrator.</p>
            </div>

            <div class="p-6 space-y-5">

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" class="w-full p-2.5 rounded-lg border {{ $errors->has('name') ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-sm" required autofocus>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIP --}}
                <div>
                    <label for="nip" class="block text-sm font-medium text-slate-700 mb-1">NIP <span class="text-slate-400 font-normal text-xs">(opsional, 18 digit)</span></label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip', $admin->nip) }}" maxlength="18" class="w-full p-2.5 rounded-lg border font-mono {{ $errors->has('nip') ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-sm" placeholder="18 digit NIP">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" class="w-full p-2.5 rounded-lg border {{ $errors->has('email') ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-sm" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Change Password Section --}}
        <div class="bg-white rounded-xl border border-gray-300 overflow-hidden mt-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-base font-semibold text-slate-800">Ubah Password</h2>
                <p class="text-sm text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="w-full p-2.5 rounded-lg border {{ $errors->has('new_password') ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-sm" placeholder="Minimal 8 karakter">
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('kelola-admin.index') }}" class="px-5 py-2.5 bg-white text-slate-700 border border-slate-300 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
