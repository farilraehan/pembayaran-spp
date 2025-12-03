<?php

namespace App\Http\Controllers;

use App\Models\JenisLaporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Keuangan';
        $laporan = JenisLaporan::where('file','!=','0')
            ->orderBy('urut','ASC')
            ->get();
        return view('Laporan_Keuangan.index', compact('title','laporan'));
    }
}
