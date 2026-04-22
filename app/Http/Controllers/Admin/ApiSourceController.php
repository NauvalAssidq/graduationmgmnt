<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSource;
use App\Models\BukuWisuda;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiSourceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_buku' => 'required|string|max:255',
            'tahun'     => 'required|digits:4',
            'api_url'   => 'required|url',
        ]);

        // Create a corresponding BukuWisuda in MySQL
        $buku = BukuWisuda::create([
            'nama_buku'      => $validated['nama_buku'],
            'tahun'          => $validated['tahun'],
            'gelombang'      => '1',
            'tanggal_terbit' => now()->toDateString(),
            'status'         => 'Published',
        ]);

        try {
            $page = 1;
            $apiUrl = $validated['api_url'];
            do {
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($apiUrl, ['page' => $page]);
                
                if (!$response->successful()) {
                    throw new \Exception("API mengembalikan status " . $response->status());
                }
                
                $data = $response->json();
                $items = $data['data'] ?? [];
                
                foreach ($items as $item) {
                    // Import to local MySQL table
                    \App\Models\Wisudawan::updateOrCreate(
                        ['nim' => $item['nim']],
                        [
                            'buku_wisuda_id'=> $buku->buku_wisuda_id,
                            'nama'          => $item['nama'] ?? '-',
                            'nomor'         => $item['nomor'] ?? '-',
                            'ttl'           => $item['ttl'] ?? '-',
                            'jenis_kelamin' => $item['jenis_kelamin'] ?? 'L',
                            'prodi'         => $item['prodi'] ?? '-',
                            'fakultas'      => $item['fakultas'] ?? '-',
                            'ipk'           => $item['ipk'] ?? 0,
                            'ka_yudisium'   => $item['ka_yudisium'] ?? '-',
                            'judul_thesis'  => $item['judul_thesis'] ?? '-',
                            'foto'          => $item['foto'] ?? null,
                        ]
                    );
                }
                
                $lastPage = $data['last_page'] ?? 1;
                $page++;
            } while ($page <= $lastPage);
            
        } catch (\Exception $e) {
            $buku->delete(); // rollback
            return back()->withErrors(['api_url' => 'Gagal sinkronisasi data API: ' . $e->getMessage()])->withInput();
        }

        ApiSource::create([
            'nama_buku'      => $validated['nama_buku'],
            'tahun'          => $validated['tahun'],
            'api_url'        => $validated['api_url'],
            'buku_wisuda_id' => $buku->buku_wisuda_id,
        ]);

        return redirect()->route('settings.api')
                         ->with('success', 'Sumber data API berhasil ditambahkan.');
    }

    public function destroy(ApiSource $apiSource)
    {
        // Delete linked buku_wisuda first (cascade to wisudawan)
        if ($apiSource->buku_wisuda_id) {
            BukuWisuda::find($apiSource->buku_wisuda_id)?->delete();
        }

        $apiSource->delete();

        return redirect()->route('settings.api')
                         ->with('success', 'Sumber data API berhasil dihapus.');
    }
}
