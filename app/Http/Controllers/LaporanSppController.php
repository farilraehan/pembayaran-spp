<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tahun_Akademik;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanSppController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Laporan SPP';

        $tgl_awal = $request->tgl_awal
            ?? Carbon::now()->startOfMonth()->format('Y-m-d');

        $tgl_akhir = $request->tgl_akhir
            ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $tahunAkademik = Tahun_Akademik::where('status', 'aktif')
            ->orderBy('nama_tahun', 'desc')
            ->get();

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('laporan_spp.index', compact(
            'title',
            'tgl_awal',
            'tgl_akhir',
            'tahunAkademik',
            'kelas'
        ));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'tgl_awal'           => 'required|date',
            'tgl_akhir'          => 'required|date',
            'jenis_laporan'      => 'required|in:spp,daftar_ulang',
            'kelas_id'           => 'nullable|exists:kelas,id',
            'tahun_akademik_id'  => 'nullable|exists:tahun_akademik,id',
        ]);

        $laporan = $request->jenis_laporan;
        $data    = $request->all();

        $logoPath = public_path('assets/img/apple-icon.png');
        if (file_exists($logoPath)) {
            $data['logo'] = base64_encode(file_get_contents($logoPath));
            $data['logo_type'] = pathinfo($logoPath, PATHINFO_EXTENSION);
        }

        if (method_exists($this, $laporan)) {
            return $this->$laporan($data);
        }

        if (view()->exists("laporan_spp.views.{$laporan}")) {
            return view("laporan_spp.views.{$laporan}", $data);
        }

        abort(404, 'Laporan SPP tidak ditemukan');
    }

    private function spp(array $data)
    {
        $pdf = Pdf::loadView('laporan_spp.views.pembayaran_spp', $data)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'enable-local-file-access' => true,
            ]);

        return $pdf->stream('laporan-spp.pdf');
    }


    private function daftar_ulang(array $data)
    {
        $pdf = Pdf::loadView('laporan_spp.views.daftar_ulang', $data)
        ->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream('laporan-spp.pdf');
    }
}
