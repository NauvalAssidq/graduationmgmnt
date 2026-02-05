@extends('layouts.dashboard')

@section('header', 'Edit Template')

@section('content')
        <x-breadcrumb :items="['Kelola Template' => route('template.index'), 'Edit Template' => null]" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Edit Template</h1>
            <p class="text-slate-500 text-sm mt-1">Perbarui konfigurasi template buku wisuda.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-6">
            <form action="{{ route('template.update', $template->nama) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Template (ID)</label>
                        <input type="text" name="nama" id="nama" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('nama', $template->nama) }}" required>
                        <p class="text-xs text-slate-400 mt-1">Perhatian: Mengubah nama template dapat mempengaruhi buku yang menggunakan ID lama.</p>
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div>
                            <label for="layout" class="block text-sm font-medium text-slate-700 mb-1">Layout Type</label>
                            <select name="layout" id="layout" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                                <option value="A4" {{ old('layout', $template->layout) == 'A4' ? 'selected' : '' }}>A4 (Standar)</option>
                                <option value="F4" {{ old('layout', $template->layout) == 'F4' ? 'selected' : '' }}>F4 (Legal)</option>
                                <option value="Booklet" {{ old('layout', $template->layout) == 'Booklet' ? 'selected' : '' }}>Booklet (A5)</option>
                            </select>
                            @error('layout') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="style" class="block text-sm font-medium text-slate-700 mb-1">Style Class</label>
                            <input type="text" name="style" id="style" class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" value="{{ old('style', $template->style) }}" placeholder="Contoh: modern-theme" required>
                            @error('style') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wider border-b border-gray-100 pb-2">Custom Front Matter & Styling</h3>
                    
                    <div>
                        <p class="text-xs text-slate-500 mb-2">Editor HTML untuk Cover dan Front Matter.</p>
                        <x-code-editor 
                            name="cover_html" 
                            label="Cover & Front Matter HTML" 
                            :value="old('cover_html', $template->cover_html)" 
                            mode="htmlmixed" 
                            height="400px" 
                        />
                        @error('cover_html') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                         <p class="text-xs text-slate-500 mb-2">CSS tambahan untuk styling template ini.</p>
                        <x-code-editor 
                            name="custom_css" 
                            label="Custom CSS" 
                            :value="old('custom_css', $template->custom_css)" 
                            mode="css"
                            height="300px" 
                        />
                        @error('custom_css') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('template.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 text-sm font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Perbarui Template</button>
                </div>
            </form>
        </div>

@endsection
