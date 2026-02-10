@extends('layouts.dashboard')

@section('header', 'Dashboard')

@section('content')
    <div class="mb-6">
        <x-breadcrumb :items="[]" />
    </div>

    <!-- Dashboard Welcome Banner -->
    <div x-data="{ show: localStorage.getItem('hide_welcome_banner') !== 'false' }" 
         x-show="show" 
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="relative mb-8 rounded-2xl overflow-hidden bg-white border border-gray-300 shadow-sm">
        
        <!-- Subtle Decor -->
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 rounded-full bg-emerald-50 blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-64 h-64 rounded-full bg-blue-50 blur-3xl opacity-60 pointer-events-none"></div>
        
        <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="max-w-3xl z-10">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                        Admin Area
                    </span>
                    <span class="text-slate-400 text-sm font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
                
                <h2 class="text-3xl font-bold text-slate-800 mb-2 tracking-tight">Halo, {{ Auth::user()->name ?? 'Admin' }}! 👋</h2>
                <p class="text-slate-500 text-base leading-relaxed mb-6 font-normal max-w-2xl">
                    Selamat datang kembali di <span class="text-emerald-700 font-semibold">SIM Buku Wisuda</span>. Pantau statistik wisudawan dan kelola publikasi buku wisuda Anda hari ini.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('wisudawan.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all text-sm flex items-center gap-2 group">
                        <svg class="w-4 h-4 text-emerald-100 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Data
                    </a>
                    <a href="{{ route('buku-wisuda.create') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-slate-700 font-medium rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all text-sm shadow-sm hover:shadow text-center">
                        Buat Buku Baru
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative z-10 opacity-90">
                <div class="w-24 h-24 bg-gradient-to-tr from-emerald-100 to-white rounded-2xl flex items-center justify-center border border-emerald-50/50 shadow-sm rotate-3">
                    <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Close Button -->
        <button @click="show = false; localStorage.setItem('hide_welcome_banner', 'false')" 
                class="absolute top-4 right-4 p-2 rounded-full text-slate-300 hover:text-slate-500 hover:bg-gray-100 transition-all z-20"
                title="Sembunyikan">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Filter Row -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="w-full">
            <div class="bg-white p-4 rounded-xl border border-gray-300 shadow-sm flex items-center gap-4">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span class="text-sm font-semibold text-slate-600 shrink-0">Filter:</span>
                <div class="w-48">
                    <x-select 
                        name="tahun" 
                        placeholder="Semua Tahun"
                        :options="$tahunList->mapWithKeys(fn($t) => [$t => $t])" 
                        :value="$selectedTahun"
                        :submitOnChange="true"
                    />
                </div>
                <div class="w-48">
                    <x-select 
                        name="gelombang" 
                        placeholder="Semua Gelombang"
                        :options="$gelombangList->mapWithKeys(fn($g) => [$g => 'Gelombang ' . $g])" 
                        :value="$selectedGelombang"
                        :submitOnChange="true"
                    />
                </div>
                <div class="ml-auto flex items-center gap-3 shrink-0">
                    @if($selectedTahun || $selectedGelombang)
                        <a href="{{ route('admin.dashboard') }}" class="text-xs text-red-500 hover:text-red-700 font-medium underline transition-colors">Reset Filter</a>
                    @endif
                    <span class="text-xs text-slate-400 font-medium">
                        {{ ($selectedTahun || $selectedGelombang) ? 'Memfilter data...' : 'Menampilkan semua data' }}
                    </span>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-white p-6 rounded-xl border border-gray-300 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Wisudawan</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalGraduates) }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-6 rounded-xl border border-gray-300 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Buku</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalBooks) }}</h3>
            </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-6 rounded-xl border border-gray-300 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Rata-rata IPK</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $avgIpk }}</h3>
            </div>
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>

        <!-- Stat Card 4 -->
            <div class="bg-white p-6 rounded-xl border border-gray-300 shadow-sm flex items-center justify-between">
            <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cumlaude</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalCumlaude) }}</h3>
            </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
        </div>
    </div>



    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8" x-data="{ showFacultyModal: false }">
        
        <!-- Main Chart (Bar) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-300 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800">Distribusi per Fakultas</h3>
                <button @click="showFacultyModal = true" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium hover:underline">
                    Lihat Detail
                </button>
            </div>
            <div class="relative h-[300px] w-full">
                <canvas id="facultyChart"></canvas>
            </div>

            <!-- Faculty Detail Modal -->
            <div x-show="showFacultyModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                <div x-show="showFacultyModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div x-show="showFacultyModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.away="showFacultyModal = false" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Detail Distribusi Fakultas</h3>
                                        <div class="mt-4 max-h-96 overflow-y-auto">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Fakultas</th>
                                                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                    @foreach($graduatesByFaculty as $faculty => $count)
                                                        <tr>
                                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $faculty }}</td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">{{ $count }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="button" @click="showFacultyModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Chart (Doughnut) -->
        <div class="bg-white p-6 rounded-xl border border-gray-300 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Ratio Gender</h3>
            <div class="relative h-[250px] w-full flex items-center justify-center">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('buku-wisuda.index') }}" class="group block p-6 bg-white rounded-xl border border-gray-300 hover:border-emerald-500 hover:shadow-md transition-all">
            <h4 class="font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Kelola Buku Wisuda</h4>
            <p class="text-sm text-slate-500 mt-2">Buat, edit, dan publikasikan buku wisuda.</p>
        </a>
            <a href="{{ route('wisudawan.index') }}" class="group block p-6 bg-white rounded-xl border border-gray-300 hover:border-emerald-500 hover:shadow-md transition-all">
            <h4 class="font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Data Wisudawan</h4>
            <p class="text-sm text-slate-500 mt-2">Import data, upload foto, dan kelola alumni.</p>
        </a>
            <a href="{{ route('admin.arsip.index') }}" class="group block p-6 bg-white rounded-xl border border-gray-300 hover:border-emerald-500 hover:shadow-md transition-all">
            <h4 class="font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Cetak Arsip</h4>
            <p class="text-sm text-slate-500 mt-2">Generate PDF dan cetak dokumen fisik.</p>
        </a>
    </div>

    <!-- Chart Scripts -->
    <script>
        // Faculty Chart
        const facultyCtx = document.getElementById('facultyChart').getContext('2d');
        const facultyData = @json($graduatesByFaculty);
        
        new Chart(facultyCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(facultyData),
                datasets: [{
                    label: 'Jumlah Wisudawan',
                    data: Object.values(facultyData),
                    backgroundColor: '#10b981', // Emerald 500
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Gender Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderData = @json($genderRatio);

        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(genderData).map(k => k === 'L' ? 'Laki-laki' : 'Perempuan'),
                datasets: [{
                    data: Object.values(genderData),
                    backgroundColor: ['#3b82f6', '#ec4899'], // Blue, Pink
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });


    </script>
@endsection
