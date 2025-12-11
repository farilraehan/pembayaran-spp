<?php

namespace App\Http\Controllers;

use App\Models\JenisLaporan;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\Profil;
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

        //logo
        $logoPath = public_path('assets/img/apple-icon.png');
        if (file_exists($logoPath)) {
            $data['logo'] = base64_encode(file_get_contents($logoPath));
            $data['logo_type'] = pathinfo($logoPath, PATHINFO_EXTENSION);
        }

        if ($laporan === 'buku_besar') {
            $data['kode_akun'] = $request->sub_laporan;
            $data['laporan']   = 'buku_besar';
            return $this->buku_besar($data);
        }

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
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl = $thn . '-' . $bln . '-' . $hari;

        $data['tahun']     = $thn;
        $data['judul']     = 'LAPORAN KEUANGAN';
        $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
        $data['tgl']       = Tanggal::tahun($tgl);
        $data['title']     = 'LAPORAN KEUANGAN';
        if (!empty($data['bulan'])) {
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl']       = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        }

        $data['profil'] = Profil::first();
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


        // Ambil rekening yang dipilih
        $rek = Rekening::where('kode_akun', $data['kode_akun'])->first();
        if (!$rek) {
            return abort(404, 'Rekening tidak ditemukan!');
        }
        $data['rek'] = $rek;
        $data['judul'] = "Buku Besar " . ($rek->kode_akun ?? '-') . " (" . Tanggal::namaBulan($tgl_awal_bulan) . " $thn)";

        // Saldo Awal Tahun
        $saldo_awal = Transaksi::where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->where('tanggal_transaksi', '<', $tgl_awal_tahun)
            ->get()
            ->reduce(fn($carry, $trx) => $carry + (
                $trx->rekening_debit == $rek->id
                    ? ($rek->jenis_mutasi == 'debet' ? $trx->jumlah : -$trx->jumlah)
                    : ($rek->jenis_mutasi == 'debet' ? -$trx->jumlah : $trx->jumlah)
            ), 0);
        $data['saldo_awal'] = $saldo_awal;

        // Kumulatif s/d Bulan Lalu
        $transaksi_bulan_lalu = Transaksi::where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_tahun, date('Y-m-d', strtotime("$tgl_awal_bulan -1 day"))])
            ->get();

        $komulatif_bulan_lalu = $transaksi_bulan_lalu->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->id) {
                $carry['debit'] += $trx->jumlah;
                $carry['saldo'] += ($rek->jenis_mutasi == 'debet' ? $trx->jumlah : -$trx->jumlah);
            } elseif ($trx->rekening_kredit == $rek->id) {
                $carry['kredit'] += $trx->jumlah;
                $carry['saldo'] += ($rek->jenis_mutasi == 'debet' ? -$trx->jumlah : $trx->jumlah);
            }
            return $carry;
        }, ['debit' => 0, 'kredit' => 0, 'saldo' => $saldo_awal]);

        $data['komulatif_bulan_lalu_debit']  = $komulatif_bulan_lalu['debit'];
        $data['komulatif_bulan_lalu_kredit'] = $komulatif_bulan_lalu['kredit'];
        $data['komulatif_bulan_lalu_saldo']  = $komulatif_bulan_lalu['saldo'];

        // Transaksi Bulan Ini
        $transaksi_bulan_ini = Transaksi::with('user')
            ->where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
            ->orderBy('tanggal_transaksi')
            ->get();
        $data['transaksi'] = $transaksi_bulan_ini;

        $total_bulan_ini = $transaksi_bulan_ini->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->id) {
                $carry['debit'] += $trx->jumlah;
            } elseif ($trx->rekening_kredit == $rek->id) {
                $carry['kredit'] += $trx->jumlah;
            }
            return $carry;
        }, ['debit' => 0, 'kredit' => 0]);
        $data['total_bulan_ini'] = $total_bulan_ini;

        // Total s/d Bulan Ini (Jan - Bulan Ini)
        $data['total_sampai_bulan_ini'] = [
            'debit'  => $komulatif_bulan_lalu['debit'] + $total_bulan_ini['debit'],
            'kredit' => $komulatif_bulan_lalu['kredit'] + $total_bulan_ini['kredit'],
            'saldo'  => $komulatif_bulan_lalu['saldo']
                    + ($rek->jenis_mutasi == 'debet'
                            ? $total_bulan_ini['debit'] - $total_bulan_ini['kredit']
                            : $total_bulan_ini['kredit'] - $total_bulan_ini['debit']),
        ];

        // Total Kumulatif Tahun (sampai Desember)
        $transaksi_tahun_ini = Transaksi::where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_tahun, "$thn-12-31"])
            ->get();

        $total_tahun_ini = $transaksi_tahun_ini->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->id) {
                $carry['debit'] += $trx->jumlah;
            } elseif ($trx->rekening_kredit == $rek->id) {
                $carry['kredit'] += $trx->jumlah;
            }
            return $carry;
        }, ['debit' => 0, 'kredit' => 0]);
        $data['total_tahun_ini'] = $total_tahun_ini;

        // Sub Judul + tanggal
        $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl_awal_bulan) . ' ' . $thn;
        $data['tgl_awal_bulan']  = $tgl_awal_bulan;
        $data['tgl_akhir_bulan'] = $tgl_akhir_bulan;
        $data['tahun'] = $thn;
        $data['bulan'] = $bln;

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
