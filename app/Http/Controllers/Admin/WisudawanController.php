<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisudawan;
use App\Models\BukuWisuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WisudawanController extends Controller
{
    public function index(Request $request)
    {
        $proxy = Request::create(route('api.wisudawan.index'), 'GET', $request->all());
        
        $response = Route::dispatch($proxy);
        
        $data = json_decode($response->getContent());
        
        $items = collect($data->data)->map(function ($item) {
            $w = new Wisudawan();
            $w->forceFill((array)$item);
            $w->exists = true;
            
            if (isset($item->buku_wisuda)) {
                $relation = new BukuWisuda();
                $relation->forceFill((array)$item->buku_wisuda);
                $relation->exists = true;
                $w->setRelation('bukuWisuda', $relation);
            }
            return $w;
        });

        // Recreate Paginator
        $graduates = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $data->total,
            $data->per_page,
            $data->current_page,
            ['path' => url()->current()]
        )->withQueryString();

        $faculties = Wisudawan::distinct()->pluck('fakultas')->sort()->values();
        $prodis = Wisudawan::distinct()->pluck('prodi')->sort()->values();
        $predikats = Wisudawan::distinct()->pluck('ka_yudisium')->sort()->values();

        return view('admin.wisudawan.index', compact('graduates', 'faculties', 'prodis', 'predikats'));
    }

    public function create()
    {
        $books = BukuWisuda::where('status', '!=', 'Archived')->get();
        return view('admin.wisudawan.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_buku' => 'required|exists:buku_wisuda,id',
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

    public function edit(Wisudawan $wisudawan)
    {
        $books = BukuWisuda::where('status', '!=', 'Archived')->get();
        return view('admin.wisudawan.edit', compact('wisudawan', 'books'));
    }

    public function update(Request $request, Wisudawan $wisudawan)
    {
        $validated = $request->validate([
            'id_buku' => 'required|exists:buku_wisuda,id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:wisudawan,nim,' . $wisudawan->id,
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

    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:5048',
            'id_buku' => 'required|exists:buku_wisuda,id',
        ]);

        $file = $request->file('file_csv');
        $bukuId = $request->id_buku;

        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');

        $count = 0;
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            try {
                Wisudawan::create([
                    'id_buku' => $bukuId,
                    'nim' => $row[0] ?? null,
                    'nama' => $row[1] ?? null,
                    'nomor' => $row[2] ?? '-',
                    'ttl' => $row[3] ?? '-',
                    'jenis_kelamin' => $row[4] ?? 'L',
                    'prodi' => $row[5] ?? '-',
                    'fakultas' => $row[6] ?? '-',
                    'ipk' => isset($row[7]) ? floatval($row[7]) : 0.0,
                    'ka_yudisium' => $row[8] ?? '-',
                    'judul_thesis' => $row[9] ?? '-',
                    'foto' => null, 
                ]);
                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }
        fclose($handle);

        return redirect()->route('wisudawan.index')->with('success', "Berhasil mengimpor $count data wisudawan.");
    }
}
