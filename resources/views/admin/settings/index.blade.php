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
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('settings.index', ['tab' => 'account']) }}" class="{{ $tab === 'account' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Pengaturan Akun
            </a>
            <a href="{{ route('settings.index', ['tab' => 'api']) }}" class="{{ $tab === 'api' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Sumber Data API
            </a>
        </nav>
    </div>

    @if($tab === 'account')
        <!-- Account Settings -->
        <form action="{{ route('settings.account.update') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-xl border border-gray-300 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-semibold text-slate-800">Pengaturan Akun</h2>
                    <p class="text-sm text-slate-500">Perbarui email dan kata sandi akun Anda.</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                            <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            Simpan Akun
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <!-- API Sources Management -->
        <div class="bg-white rounded-xl border border-gray-300 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Sumber Data API</h2>
                    <p class="text-sm text-slate-500">Kelola endpoint API untuk mengambil data wisudawan dari luar.</p>
                </div>
                <button type="button" onclick="document.getElementById('apiModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah API
                </button>
            </div>
            
            <div class="p-0">
                @if($apiSources->isEmpty())
                    <div class="p-12 text-center flex flex-col items-center">
                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        <p class="font-medium text-slate-600">Belum ada sumber data API</p>
                        <p class="text-sm text-slate-400 mt-1">Tambahkan API eksternal untuk mengimpor wisudawan ke dalam Buku Wisuda baru.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Nama Buku Wisuda</th>
                                    <th class="px-6 py-3 font-semibold">Tahun</th>
                                    <th class="px-6 py-3 font-semibold">API Endpoint</th>
                                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($apiSources as $source)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $source->nama_buku }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $source->tahun }}</td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-xs break-all">{{ $source->api_url }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('api-sources.destroy', $source) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin? Menghapus API ini juga akan menghapus buku wisuda yang terkait beserta isinya.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Add API Modal -->
        <div id="apiModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all w-full max-w-lg border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900" id="modal-title">Tambah Sumber API</h3>
                        <button type="button" onclick="document.getElementById('apiModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('api-sources.store') }}" method="POST">
                        @csrf
                        <div class="px-6 py-5 space-y-4">
                            <p class="text-sm text-slate-500">
                                Menambahkan sumber API akan membuat sebuah Buku Wisuda baru yang terhubung ke endpoint ini.
                            </p>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Buku Wisuda</label>
                                <input type="text" name="nama_buku" placeholder="Misal: Buku Wisuda 2024 Gelombang I" class="block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500" required/>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                                <input type="text" name="tahun" placeholder="Misal: 2024" maxlength="4" pattern="\d{4}" class="block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500" required/>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">URL API Endpoint</label>
                                <input type="url" name="api_url" placeholder="https://api.kampus.ac.id/wisudawan" class="block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500" required/>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                            <button type="button" onclick="document.getElementById('apiModal').classList.add('hidden')" class="px-4 py-2 bg-white text-slate-700 border border-slate-300 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm focus:outline-none">
                                Simpan & Buat Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
