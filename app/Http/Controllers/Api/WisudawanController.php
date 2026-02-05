<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wisudawan;
use Illuminate\Http\Request;

class WisudawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisudawan::with('bukuWisuda');

        // Advanced Search / Filter
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

        // Sort
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
