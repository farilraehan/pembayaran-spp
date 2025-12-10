<?php

namespace App\Http\Controllers;

use App\Models\JenisLaporan;
use App\Models\Rekening;
use App\Utils\Tanggal;
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
    public function subLaporan($file)
    {
         if ($file == 'buku_besar') {
            $rekening = Rekening::orderBy('kode_akun', 'ASC')->get();
            $sub_laporan = [];

            foreach ($rekening as $rek) {
                $sub_laporan[] = [
                    'value' => $rek->kode_akun,
                    'title' => $rek->kode_akun . '. ' . $rek->nama_akun
                ];
            }

            return view('laporan.partials.sub_laporan', [
                'type' => 'select',
                'sub_laporan' => $sub_laporan
            ]);
        }
         else {
            $sub_laporan = [
                [
                    'value' => '',
                    'title' => '---'
                ]
            ];

            return view('laporan.partials.sub_laporan', [
                'type' => 'select',
                'sub_laporan' => $sub_laporan
            ]);
        }
        
    }
public function preview(Request $request)
{
    $laporan = $request->laporan;
    $data    = $request->all();

    // ✅ Tambahkan logo di awal agar ikut ke semua laporan
    $logoPath = public_path('assets/img/apple-icon.png');
    if (file_exists($logoPath)) {
        $data['logo'] = base64_encode(file_get_contents($logoPath));
        $data['logo_type'] = pathinfo($logoPath, PATHINFO_EXTENSION);
    }

    // Jika buku besar butuh sub_laporan jadi kode akun
    if ($laporan === 'buku_besar') {
        $data['kode_akun'] = $request->sub_laporan;
        $data['laporan']   = 'buku_besar';
        return $this->buku_besar($data);
    }

    // Jika ada method khusus
    if (method_exists($this, $laporan)) {
        return $this->$laporan($data);
    }

    // Jika hanya view biasa
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

    private function buku_besar(array $data)
    {   
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        $tgl_awal_tahun  = "$thn-01-01";
        $tgl_awal_bulan  = "$thn-$bln-01";
        $tgl_akhir_bulan = "$thn-$bln-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);


        $rek = Rekening::where('kode_akun', $data['kode_akun'])->first();
        if (!$rek) {
            return abort(404, 'Rekening tidak ditemukan!');
        }
        $data['rek'] = $rek;
        $data['judul'] = "Buku Besar " . ($rek->kode_akun ?? '-') . " (" . Tanggal::namaBulan($tgl_awal_bulan) . " $thn)";
        $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl_awal_bulan) . ' ' . $thn;

        $view = view('laporan.views.buku_besar', $data)->render();

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
