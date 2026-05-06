<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSource;
use App\Models\BukuWisuda;
use App\Models\Wisudawan;
use App\Models\Api\WisudawanApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiSourceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_buku' => 'required|string|max:255',
            'tahun'     => 'required|digits:4',
            'api_url'   => 'required|url',
        ]);

        $buku = BukuWisuda::create([
            'nama_buku'      => $validated['nama_buku'],
            'tahun'          => $validated['tahun'],
            'gelombang'      => '1',
            'tanggal_terbit' => now()->toDateString(),
            'status'         => 'Published',
        ]);

        try {
            $this->syncFromApi($validated['api_url'], $buku->buku_wisuda_id);
        } catch (\Exception $e) {
            $buku->delete();
            return back()
                ->withErrors(['api_url' => 'Gagal sinkronisasi data API: ' . $e->getMessage()])
                ->withInput();
        }

        ApiSource::create([
            'nama_buku'      => $validated['nama_buku'],
            'tahun'          => $validated['tahun'],
            'api_url'        => $validated['api_url'],
            'buku_wisuda_id' => $buku->buku_wisuda_id,
        ]);

        $count = Wisudawan::where('buku_wisuda_id', $buku->buku_wisuda_id)->count();

        return redirect()->route('settings.index')
                         ->with('success', "Sumber data API berhasil ditambahkan dan {$count} wisudawan berhasil diimpor.");
    }


    private function syncFromApi(string $apiUrl, int $bukuId): void
    {
        if ($this->isLocalApiUrl($apiUrl)) {
            $this->syncFromSqliteDirect($bukuId);
        } else {
            $this->syncViaHttp($apiUrl, $bukuId);
        }
    }


    private function isLocalApiUrl(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST));

        $localHosts = ['127.0.0.1', 'localhost', '[::1]', '::1'];

        $appHost = strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?? '');
        if ($appHost) {
            $localHosts[] = $appHost;
        }

        return in_array($host, $localHosts, true);
    }

    private function syncFromSqliteDirect(int $bukuId): void
    {
        WisudawanApi::chunk(200, function ($rows) use ($bukuId) {
            foreach ($rows as $item) {
                Wisudawan::updateOrCreate(
                    ['nim' => $item->nim],
                    [
                        'buku_wisuda_id' => $bukuId,
                        'nama'           => $item->nama          ?? '-',
                        'nomor'          => $item->nomor         ?? '-',
                        'ttl'            => $item->ttl           ?? '-',
                        'jenis_kelamin'  => $item->jenis_kelamin ?? 'L',
                        'prodi'          => $item->prodi         ?? '-',
                        'fakultas'       => $item->fakultas      ?? '-',
                        'ipk'            => $item->ipk           ?? 0,
                        'ka_yudisium'    => $item->ka_yudisium   ?? '-',
                        'judul_thesis'   => $item->judul_thesis  ?? '-',
                        'foto'           => $item->foto          ?? null,
                    ]
                );
            }
        });
    }

    /**
     * Fetch paginated JSON from a real external HTTP API and upsert into MySQL.
     */
    private function syncViaHttp(string $apiUrl, int $bukuId): void
    {
        $page = 1;

        do {
            $response = Http::timeout(30)->get($apiUrl, ['page' => $page]);

            if (!$response->successful()) {
                throw new \Exception("API mengembalikan status HTTP " . $response->status());
            }

            $data     = $response->json();
            $items    = $data['data']      ?? [];
            $lastPage = $data['last_page'] ?? 1;

            foreach ($items as $item) {
                Wisudawan::updateOrCreate(
                    ['nim' => $item['nim']],
                    [
                        'buku_wisuda_id' => $bukuId,
                        'nama'           => $item['nama']          ?? '-',
                        'nomor'          => $item['nomor']         ?? '-',
                        'ttl'            => $item['ttl']           ?? '-',
                        'jenis_kelamin'  => $item['jenis_kelamin'] ?? 'L',
                        'prodi'          => $item['prodi']         ?? '-',
                        'fakultas'       => $item['fakultas']      ?? '-',
                        'ipk'            => $item['ipk']           ?? 0,
                        'ka_yudisium'    => $item['ka_yudisium']   ?? '-',
                        'judul_thesis'   => $item['judul_thesis']  ?? '-',
                        'foto'           => $item['foto']          ?? null,
                    ]
                );
            }

            $page++;
        } while ($page <= $lastPage);
    }

    public function destroy(ApiSource $apiSource)
    {
        // Deleting the BukuWisuda cascades to all linked wisudawan via FK
        if ($apiSource->buku_wisuda_id) {
            BukuWisuda::find($apiSource->buku_wisuda_id)?->delete();
        }

        $apiSource->delete();

        return redirect()->route('settings.index')
                         ->with('success', 'Sumber data API berhasil dihapus.');
    }
}
