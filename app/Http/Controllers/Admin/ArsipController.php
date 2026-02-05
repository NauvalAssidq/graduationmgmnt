<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuWisuda;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuWisuda::where('status', 'Published')->orWhereNotNull('file_pdf');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_buku', 'like', "%{$search}%")
                  ->orWhere('gelombang', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->input('sort_by', 'tanggal_terbit');
        $sortDirection = $request->input('sort_order', 'desc');
        $allowedSorts = ['nama_buku', 'gelombang', 'tahun', 'tanggal_terbit', 'status', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $archives = $query->paginate(10)->withQueryString();
        return view('admin.arsip.index', compact('archives'));
    }

    public function generatePdf($id)
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $book = BukuWisuda::with(['wisudawan' => function($q) {
            $q->orderBy('fakultas')->orderBy('prodi')->orderBy('nama');
        }])->findOrFail($id);

        // Toggle Logic: If PDF exists, delete it
        if ($book->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->file_pdf)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_pdf);
            $book->update(['file_pdf' => null]);
            return back()->with('success', 'PDF berhasil dihapus.');
        }

        // Generate Logic
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('buku_wisuda');

        // Determine filename
        $slug = $book->slug ?? \Illuminate\Support\Str::slug($book->nama_buku) . '-' . $book->id;
        $filename = "buku_wisuda/{$slug}.pdf";
        
        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.arsip.print_book', ['book' => $book, 'isPdf' => true]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['isRemoteEnabled' => true, 'dpi' => 150]);
        
        $pdf->save(storage_path('app/public/' . $filename));

        $book->update(['file_pdf' => $filename]);

        return back()->with('success', 'PDF berhasil dibuat.');
    }
}
