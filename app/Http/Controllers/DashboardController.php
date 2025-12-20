<?php

namespace App\Http\Controllers;

use App\Models\Jenis_Biaya;
use App\Models\Siswa;
use App\Models\Spp;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        $siswaCount = Siswa::count();
        $jenis_biaya = Jenis_Biaya::all();
        $siswaAktif = Siswa::where('status_siswa', 'aktif')->count();
        $siswaNonaktif = Siswa::where('status_siswa', 'nonaktif')->count();

        return view('dashboard.index', compact('siswa', 'siswaCount', 'jenis_biaya', 'siswaAktif', 'siswaNonaktif'));
    }
}
