<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanSppController extends Controller
{
    public function index()
    {
        $title = 'Laporan Spp';
        return view('laporan_spp.index', compact('title'));
    }
}
