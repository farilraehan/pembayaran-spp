<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tahun_Akademik;
use App\Models\Kelas;
class LaporanSppController extends Controller
{
    public function index(Request $request)
{
    $title = 'Laporan Spp';

    $tgl_awal  = $request->tgl_awal
        ?? Carbon::now()->startOfMonth()->format('Y-m-d');

    $tgl_akhir = $request->tgl_akhir
        ?? Carbon::now()->endOfMonth()->format('Y-m-d');

$tahunAkademik = Tahun_Akademik::orderBy('nama_tahun', 'desc')->get();
$kelas = Kelas::orderBy('nama_kelas')->get();

    return view('laporan_spp.index', compact(
        'title',
        'tgl_awal',
        'tgl_akhir',
        'tahunAkademik',
        'kelas'
    ));
}
}
