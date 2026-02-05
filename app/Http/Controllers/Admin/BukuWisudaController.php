<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuWisuda;
use App\Models\TemplateBukuWisuda;
use Illuminate\Http\Request;

class BukuWisudaController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuWisuda::query()
            ->withCount('wisudawan'); // Eager load count

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_buku', 'like', "%{$search}%")
                  ->orWhere('gelombang', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Year
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['nama_buku', 'tahun', 'gelombang', 'status', 'created_at', 'tanggal_terbit', 'wisudawan_count'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $books = $query->paginate(10)->withQueryString();
        
        // Get years for filter
        $years = BukuWisuda::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.buku_wisuda.index', compact('books', 'years'));
    }

    public function create()
    {
        $templates = TemplateBukuWisuda::all();
        return view('admin.buku_wisuda.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_buku' => 'required|string|max:255',
            'template_id' => 'nullable|string|exists:template_buku_wisuda,nama', // Added
            'tanggal_terbit' => 'required|date',
            'gelombang' => 'required|string|max:50',
            'status' => 'required|in:Published,Draft,Archived',
            'tahun' => 'required|integer|min:2000|max:'.(date('Y')+1),
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480', // Max 20MB
        ]);

        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('buku_wisuda', 'public');
            $validated['file_pdf'] = $path;
        }

        BukuWisuda::create($validated);
        return redirect()->route('buku-wisuda.index')->with('success', 'Buku Wisuda berhasil dibuat.');
    }

    public function edit(BukuWisuda $bukuWisuda)
    {
        $templates = TemplateBukuWisuda::all();
        return view('admin.buku_wisuda.edit', compact('bukuWisuda', 'templates'));
    }

    public function update(Request $request, BukuWisuda $bukuWisuda)
    {
        $validated = $request->validate([
            'nama_buku' => 'required|string|max:255',
            'template_id' => 'nullable|string|exists:template_buku_wisuda,nama', // Added
            'tanggal_terbit' => 'required|date',
            'gelombang' => 'required|string|max:50',
            'status' => 'required|in:Published,Draft,Archived',
            'tahun' => 'required|integer|min:2000|max:'.(date('Y')+1),
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('buku_wisuda', 'public');
            $validated['file_pdf'] = $path;
        }

        $bukuWisuda->update($validated);
        return redirect()->route('buku-wisuda.index')->with('success', 'Buku Wisuda berhasil diperbarui.');
    }

    public function destroy(BukuWisuda $bukuWisuda)
    {
        $bukuWisuda->delete();
        return redirect()->route('buku-wisuda.index');
    }
}
