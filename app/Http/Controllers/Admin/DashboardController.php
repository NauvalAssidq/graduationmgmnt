<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuWisuda;
use App\Models\Wisudawan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $selectedTahun = $request->input('tahun');
        $selectedGelombang = $request->input('gelombang');

        // Distinct values for filter dropdowns
        $tahunList = BukuWisuda::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $gelombangList = BukuWisuda::distinct()->orderBy('gelombang', 'asc')->pluck('gelombang');

        // Base Query with optional join for filtering
        $query = Wisudawan::query();
        if ($selectedTahun || $selectedGelombang) {
            $query->whereHas('bukuWisuda', function ($q) use ($selectedTahun, $selectedGelombang) {
                if ($selectedTahun) $q->where('tahun', $selectedTahun);
                if ($selectedGelombang) $q->where('gelombang', $selectedGelombang);
            });
        }

        $totalGraduates = $query->count();
        $avgIpk = $totalGraduates > 0 ? number_format($query->avg('ipk'), 2) : '0.00';
        $totalCumlaude = $query->clone()->where('ka_yudisium', 'like', '%Cumlaude%')->count();
        $totalBooks = BukuWisuda::count();

        $graduatesByFaculty = $query->clone()
            ->select('fakultas', DB::raw('count(*) as total'))
            ->groupBy('fakultas')
            ->pluck('total', 'fakultas')
            ->toArray();

        $genderRatio = $query->clone()
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalBooks', 
            'totalGraduates', 
            'avgIpk', 
            'totalCumlaude',
            'graduatesByFaculty',
            'genderRatio',
            'tahunList',
            'gelombangList',
            'selectedTahun',
            'selectedGelombang'
        ));
    }
}
