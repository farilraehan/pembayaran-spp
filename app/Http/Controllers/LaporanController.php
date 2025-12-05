<?php

namespace App\Http\Controllers;

use App\Models\JenisLaporan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class LaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Keuangan';
        $laporan = JenisLaporan::where('file','!=','0')
            ->orderBy('urut','ASC')
            ->get();
        return view('laporan.index', compact('title','laporan'));
    }

    public function preview(Request $request)
    {
        $laporan = $request->laporan;
        $data    = $request->all();

        if (method_exists($this, $laporan)) {
            return $this->$laporan($data);
        }

        if (view()->exists("laporan.views.{$laporan}")) {
            return view("laporan.views.{$laporan}", $data);
        }

        abort(404, 'Laporan tidak ditemukan');
    }

    private function cover(array $data)
    {
         $view = view('laporan.views.cover', $data)->render();

        $pdf = Pdf::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream();
    }
}
