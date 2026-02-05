<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuWisuda;
use App\Models\Wisudawan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = BukuWisuda::count();
        $totalGraduates = Wisudawan::count();
        $avgIpk = Wisudawan::count() > 0 ? number_format(Wisudawan::avg('ipk'), 2) : '0.00';
        $totalCumlaude = Wisudawan::where('ka_yudisium', 'like', '%Cumlaude%')->count();

        $graduatesByFaculty = Wisudawan::select('fakultas', DB::raw('count(*) as total'))
            ->groupBy('fakultas')
            ->pluck('total', 'fakultas')
            ->toArray();

        $genderRatio = Wisudawan::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalBooks', 
            'totalGraduates', 
            'avgIpk', 
            'totalCumlaude',
            'graduatesByFaculty',
            'genderRatio'
        ));
    }
}
