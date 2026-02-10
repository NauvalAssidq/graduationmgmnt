<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wisudawan;
use Illuminate\Http\Request;

use App\Models\Setting;

class WisudawanController extends Controller
{
    public function index(Request $request)
    {
        $externalUrl = Setting::where('key', 'wisudawan_api_url')->value('value');

        // implementasi API dengan route api.wisudawan.index atau api lainnya sesuai dengan link
        if ($externalUrl) {
            $response = \Illuminate\Support\Facades\Http::get($externalUrl, $request->all());
            return response()->json($response->json(), $response->status());
        }

        $query = Wisudawan::with('bukuWisuda');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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

        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_order', 'desc');
        $allowedSorts = ['nama', 'nim', 'prodi', 'fakultas', 'ipk', 'ka_yudisium', 'created_at', 'buku_wisuda.nama_buku'];

        if (in_array($sortField, $allowedSorts)) {
             $query->orderBy($sortField, $sortDirection);
        }

        $graduates = $query->paginate(20)->withQueryString();
        
        return response()->json($graduates);
    }
}
