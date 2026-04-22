<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisudawan;
use App\Models\Api\WisudawanApi;
use App\Models\BukuWisuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class WisudawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisudawan::with('bukuWisuda');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($request->filled('fakultas')) {
            $query->where('fakultas', $request->fakultas);
        }

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('yudisium')) {
            $query->where('ka_yudisium', $request->yudisium);
        }

        $sortField     = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_order', 'desc');
        $allowedSorts  = ['nama', 'nim', 'prodi', 'fakultas', 'ipk', 'ka_yudisium', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $graduates = $query->paginate(20)->withQueryString();

        $faculties = Wisudawan::distinct()->pluck('fakultas')->sort()->values();
        $prodis = Wisudawan::distinct()->pluck('prodi')->sort()->values();
        $predikats = Wisudawan::distinct()->pluck('ka_yudisium')->sort()->values();

        return view('admin.wisudawan.index', compact('graduates', 'faculties', 'prodis', 'predikats'));
    }

    // create wisudawan dan data stream nya
    public function create()
    {
        $books = BukuWisuda::where('status', '!=', 'Archived')->get();
        return view('admin.wisudawan.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_wisuda_id' => 'required|exists:buku_wisuda,buku_wisuda_id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:wisudawan,nim',
            'nomor' => 'required|string',
            'ttl' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi' => 'required|string',
            'fakultas' => 'required|string',
            'ipk' => 'required|numeric|between:0,4.00',
            'ka_yudisium' => 'required|string',
            'judul_thesis' => 'required|string',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('wisudawan', 'public');
            $validated['foto'] = $path;
        }

        Wisudawan::create($validated);
        return redirect()->route('wisudawan.index')->with('success', 'Data wisudawan berhasil ditambahkan.');
    }

    //edit wisudawan dengan data streamnya
    public function edit(Wisudawan $wisudawan)
    {
        $books = BukuWisuda::where('status', '!=', 'Archived')->get();
        return view('admin.wisudawan.edit', compact('wisudawan', 'books'));
    }

    public function update(Request $request, Wisudawan $wisudawan)
    {
        $validated = $request->validate([
            'buku_wisuda_id' => 'required|exists:buku_wisuda,buku_wisuda_id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:wisudawan,nim,' . $wisudawan->wisudawan_id,
            'nomor' => 'required|string',
            'ttl' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi' => 'required|string',
            'fakultas' => 'required|string',
            'ipk' => 'required|numeric|between:0,4.00',
            'ka_yudisium' => 'required|string',
            'judul_thesis' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($wisudawan->foto);
            $path = $request->file('foto')->store('wisudawan', 'public');
            $validated['foto'] = $path;
        }

        $wisudawan->update($validated);

        return redirect()->route('wisudawan.index')->with('success', 'Data wisudawan berhasil diperbarui.');
    }

    public function destroy(Wisudawan $wisudawan)
    {
        if ($wisudawan->foto) Storage::disk('public')->delete($wisudawan->foto);
        $wisudawan->delete();
        return redirect()->route('wisudawan.index')->with('success', 'Data wisudawan berhasil dihapus.');
    }

    // deprecated, fungsi ini tidak digunakan
    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt,xlsx,xls|max:5048',
            'gelombang' => 'required|string',
            'tahun' => 'required|string|digits:4',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\WisudawanImport($request->input('gelombang'), $request->input('tahun')),
                $request->file('file_csv')
            );
            
            return redirect()->route('wisudawan.index')->with('success', 'Data wisudawan berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('wisudawan.index')->with('error', 'Terjadi kesalahan saat impor: ' . $e->getMessage());
        }
    }
}
