@extends('layouts.dashboard')

@section('header', 'Kelola Template')

@section('content')
        <x-breadcrumb :items="['Kelola Template' => route('template.index')]" />

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Daftar Template Buku</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola variasi layout dan style untuk buku wisuda.</p>
            </div>
            <a href="{{ route('template.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Template
            </a>
        </div>

        <!-- Alpine Context for Preview -->
        <div x-data="templateIndex">

            <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50">
                    <form action="{{ route('template.index') }}" method="GET" class="w-full sm:w-96 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="pl-10 p-2 w-full rounded-lg border border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Cari template...">
                        @if(request('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    <a href="{{ route('template.index', ['sort_by' => 'nama', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group flex items-center gap-1 hover:text-emerald-700">
                                        Nama Template
                                        @if(request('sort_by') == 'nama')
                                            <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    <a href="{{ route('template.index', ['sort_by' => 'layout', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group flex items-center gap-1 hover:text-emerald-700">
                                        Layout Type
                                        @if(request('sort_by') == 'layout')
                                            <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    <a href="{{ route('template.index', ['sort_by' => 'style', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort_by', 'sort_order'])) }}" class="group flex items-center gap-1 hover:text-emerald-700">
                                        Style Class
                                        @if(request('sort_by') == 'style')
                                            <span class="text-emerald-600">{{ request('sort_order') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($templates as $template)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $template->nama }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $template->layout }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                                        {{ $template->style }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Preview Button -->
                                            <button @click="openPreview({{ json_encode($template) }})" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Preview">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <a href="{{ route('template.edit', $template->nama) }}" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('template.destroy', $template->nama) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?');">
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
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                                            <p class="font-medium text-slate-600">Belum ada template</p>
                                            <p class="text-xs text-slate-400 mt-1">Tambahkan template untuk opsi tampilan buku.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Preview Modal -->
            <div x-show="previewModalOpen" 
                 style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="previewModalOpen = false"></div>

                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-2xl transform transition-all"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        
                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Preview: <span x-text="previewName" class="font-normal text-slate-500 ml-1"></span>
                            </h3>
                            <button @click="previewModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="bg-gray-100 h-[70vh] rounded-b-xl overflow-hidden relative">
                            <div x-ref="shadowHost" class="w-full h-full"></div>
                        </div>

                        <!-- Footer -->
                        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end">
                            <button @click="previewModalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-slate-700 text-sm font-medium hover:bg-gray-50 transition-colors">
                                Tutup Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('templateIndex', () => ({
            previewModalOpen: false, 
            previewName: '',
            shadowRoot: null,
            
            openPreview(template) {
                this.previewName = template.nama;
                this.previewModalOpen = true;
                
                this.$nextTick(() => {
                    this.renderPreview(template);
                });
            },

            renderPreview(template) {
                const host = this.$refs.shadowHost;
                if (!host) return;

                if (!this.shadowRoot) {
                    this.shadowRoot = host.attachShadow({ mode: 'open' });
                }

                let htmlContent = template.cover_html || '<div style="display:flex;justify-content:center;align-items:center;height:100%;color:#ccc;">No Content</div>';
                let customCss = template.custom_css || '';

                // Determine Page Size
                let width = '210mm';
                let height = '297mm';
                
                // Normalize layout value
                const layout = String(template.layout || 'A4').trim().toUpperCase();

                switch(layout) {
                    case 'F4': width = '215mm'; height = '330mm'; break;
                    case 'BOOKLET': width = '148mm'; height = '210mm'; break;
                    default: width = '210mm'; height = '297mm'; // A4
                }

                // Auto-wrap if not already wrapped
                if (!htmlContent.includes('a4-page') && !htmlContent.includes('sheet')) {
                    htmlContent = '<div class="a4-page">' + htmlContent + '</div>';
                }

                // Construct Shadow DOM Content
                const content = `
                    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                    <style>
                        :host { display: block; width: 100%; height: 100%; overflow: auto; background: #f3f4f6; }
                        .preview-wrapper { 
                            padding: 20px; 
                            display: flex; 
                            flex-direction: column; 
                            align-items: center; 
                            gap: 20px; 
                            font-family: sans-serif; 
                            min-height: 100%;
                            box-sizing: border-box;
                        }
                        .a4-page, .sheet {
                            width: ${width};
                            height: ${height}; /* FIXED height - matches actual A4 */
                            min-height: ${height};
                            max-height: ${height};
                            background: white;
                            padding: 2.5cm;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                            position: relative;
                            color: black;
                            flex-shrink: 0;
                            box-sizing: border-box;
                            overflow: hidden; /* Clip overflow to show accurate boundaries */
                        }
                        /* Visual indicator for overflow - red border when content is clipped */
                        .a4-page::after, .sheet::after {
                            content: "";
                            position: absolute;
                            bottom: 0;
                            left: 0;
                            right: 0;
                            height: 3px;
                            background: linear-gradient(to right, transparent, #ef4444, transparent);
                            opacity: 0;
                            pointer-events: none;
                        }
                        /* User Custom CSS */
                        ${customCss}
                    </style>
                    <div class="preview-wrapper">
                        ${htmlContent}
                    </div>
                `;

                this.shadowRoot.innerHTML = content;
            }
        }));
    });
</script>
@endpush
