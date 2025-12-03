<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Keuangan';
        return view('Laporan_Keuangan.index');
    }
}
