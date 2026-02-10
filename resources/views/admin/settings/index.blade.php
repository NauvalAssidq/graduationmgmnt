@extends('layouts.dashboard')

@section('title', 'Pengaturan')

@section('header', 'Pengaturan')

@section('content')
<div class="space-y-6">
    <x-breadcrumb :items="['Pengaturan' => null]" />

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Account Settings -->
        <div class="bg-white rounded-xl border border-gray-300 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-lg font-semibold text-slate-800">Pengaturan Akun</h2>
                <p class="text-sm text-slate-500">Perbarui email dan kata sandi akun Anda.</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Integration Settings -->
        <div class="bg-white rounded-xl border border-gray-300 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-lg font-semibold text-slate-800">Integrasi Data</h2>
                <p class="text-sm text-slate-500">Konfigurasi sumber data eksternal.</p>
            </div>
            
            <div class="p-6">
                <div>
                    <label for="wisudawan_api_url" class="block text-sm font-medium text-slate-700 mb-1">Wisudawan API URL</label>
                    <input type="url" name="wisudawan_api_url" id="wisudawan_api_url" value="{{ old('wisudawan_api_url', $apiUrl) }}" placeholder="https://api.example.com/v1/wisudawan" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    <p class="mt-1 text-xs text-slate-500">Endpoint API untuk mengambil data wisudawan secara real-time. Biarkan kosong untuk menggunakan database lokal saja.</p>
                    @error('wisudawan_api_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
