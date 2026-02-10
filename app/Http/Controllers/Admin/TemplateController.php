<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateBukuWisuda;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    // Daftar template buku dalam list (index)
    public function index(Request $request)
    {
        $query = TemplateBukuWisuda::query();

        // Search dengan query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('layout', 'like', "%{$search}%")
                  ->orWhere('style', 'like', "%{$search}%");
            });
        }

        // Sort dengan query database juga
        $sortField = $request->input('sort_by', 'nama');
        $sortDirection = $request->input('sort_order', 'asc');
        $allowedSorts = ['nama', 'layout', 'style', 'created_at', 'updated_at'];

        // Array sort
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $templates = $query->paginate(10)->withQueryString();
        return view('admin.template.index', compact('templates'));
    }

    // tampilan halaman create template baru
    public function create()
    {
        return view('admin.template.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:template_buku_wisuda,nama',
            'layout' => 'required|string',
            'style' => 'required|string',
            'cover_html' => 'nullable|string',
            'custom_css' => 'nullable|string',
        ]);

        TemplateBukuWisuda::create($validated);

        return redirect()->route('template.index')->with('success', 'Template berhasil ditambahkan.');
    } // end creation

    // tampilan halaman edit template
    public function edit($nama)
    {
        $template = TemplateBukuWisuda::findOrFail($nama);
        return view('admin.template.edit', compact('template'));
    }

    // Update template yang ada
    public function update(Request $request, $nama)
    {
        $template = TemplateBukuWisuda::findOrFail($nama);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:template_buku_wisuda,nama,'.$template->nama.',nama',
            'layout' => 'required|string',
            'style' => 'required|string',
            'cover_html' => 'nullable|string',
            'custom_css' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('template.index')->with('success', 'Template berhasil diperbarui.');
    } // end update (edit)

    // Hapus template
    public function destroy($nama)
    {
        $template = TemplateBukuWisuda::findOrFail($nama);
        $template->delete();

        return redirect()->route('template.index')->with('success', 'Template berhasil dihapus.');
    }
}
