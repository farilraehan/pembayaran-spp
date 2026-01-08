<?php

namespace App\Http\Controllers;

use App\Models\Jenis_Biaya;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Transaksi;
use App\Models\Rekening;
use App\Models\Kelas;
use Carbon\Carbon;
use App\Utils\Tanggal;

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
        $siswaBlokir = Siswa::where('status_siswa', 'blokir')->count();
        $bulan = now()->format('Y-m');

        $bulan = now()->month;
        $tahun = now()->year;
        $tunggakan = Siswa::with(['getTransaksi' => function ($q) {
            $q->where('rekening_debit', '1.1.03.01')
                ->where('rekening_kredit', '4.1.01.01')
                ->with('spp'); // load spp di transaksi
        }])
            ->where('status_siswa', 'aktif')
            ->whereHas('getTransaksi', function ($q) {
                $q->where('rekening_debit', '1.1.03.01')
                    ->where('rekening_kredit', '4.1.01.01');
            })
            ->get();

        $title = 'Dashboard';

        return view('dashboard.index', compact('title', 'siswa', 'siswaCount', 'tunggakan', 'jenis_biaya', 'siswaBlokir', 'siswaAktif', 'siswaNonaktif'));
    }
}
