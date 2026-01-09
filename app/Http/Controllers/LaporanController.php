<?php

namespace App\Http\Controllers;

use App\Models\JenisLaporan;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\Profil;
use App\Models\Calk;
use App\Models\AkunLevel1;
use App\Models\MasterArusKas;
use App\Models\Tanda_tangan;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Keuangan';
        $laporan = JenisLaporan::where('file', '!=', '0')
            ->orderBy('urut', 'ASC')
            ->get();
        return view('laporan.index', compact('title', 'laporan'));
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
        } elseif ($file == 'calk') {

            $tahun = request('tahun');
            $bulan = str_pad(request('bulan'), 2, '0', STR_PAD_LEFT);

            $tanggal = "{$tahun}-{$bulan}-01";

            $calk = Calk::where('tanggal', $tanggal)->first();

            return view('laporan.partials.sub_laporan', [
                'type'       => 'textarea',
                'keterangan' => $calk->catatan ?? ''
            ]);
        } else {

            return view('laporan.partials.sub_laporan', [
                'type' => 'select',
                'sub_laporan' => [
                    ['value' => '', 'title' => '---']
                ]
            ]);
        }
    }

    public function preview(Request $request)
    {
        $laporan = $request->laporan;
        $data    = $request->all();

        // ================= LOGO =================
        $logoPath = public_path('assets/img/apple-icon.png');
        if (file_exists($logoPath)) {
            $data['logo'] = base64_encode(file_get_contents($logoPath));
            $data['logo_type'] = pathinfo($logoPath, PATHINFO_EXTENSION);
        }

        // ================= SIMPAN CALK =================
        if ($laporan === 'calk') {

            $tahun = $request->tahun;
            $bulan = str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
            $tanggal = "{$tahun}-{$bulan}-01";

            Calk::updateOrCreate(
                ['tanggal' => $tanggal],
                ['catatan' => $request->sub_laporan]
            );
        }

        // ================= BUKU BESAR =================
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
            ->where('rekening_debit', $rek->kode_akun)
            ->orWhere('rekening_kredit', $rek->kode_akun))
            ->where('tanggal_transaksi', '<', $tgl_awal_tahun)
            ->get()
            ->reduce(fn($carry, $trx) => $carry + (
                $trx->rekening_debit == $rek->kode_akun
                ? ($rek->jenis_mutasi == 'debet' ? $trx->jumlah : -$trx->jumlah)
                : ($rek->jenis_mutasi == 'debet' ? -$trx->jumlah : $trx->jumlah)
            ), 0);
        $data['saldo_awal'] = $saldo_awal;

        // Kumulatif s/d Bulan Lalu
        $transaksi_bulan_lalu = Transaksi::where(fn($q) => $q
            ->where('rekening_debit', $rek->kode_akun)
            ->orWhere('rekening_kredit', $rek->kode_akun))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_tahun, date('Y-m-d', strtotime("$tgl_awal_bulan -1 day"))])
            ->get();

        $komulatif_bulan_lalu = $transaksi_bulan_lalu->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->kode_akun) {
                $carry['debit'] += $trx->jumlah;
                $carry['saldo'] += ($rek->jenis_mutasi == 'debet' ? $trx->jumlah : -$trx->jumlah);
            } elseif ($trx->rekening_kredit == $rek->kode_akun) {
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
                ->where('rekening_debit', $rek->kode_akun)
                ->orWhere('rekening_kredit', $rek->kode_akun))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
            ->orderBy('tanggal_transaksi')
            ->get();
        $data['transaksi'] = $transaksi_bulan_ini;

        $total_bulan_ini = $transaksi_bulan_ini->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->kode_akun) {
                $carry['debit'] += $trx->jumlah;
            } elseif ($trx->rekening_kredit == $rek->kode_akun) {
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
            ->where('rekening_debit', $rek->ikode_akund)
            ->orWhere('rekening_kredit', $rek->kode_akun))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_tahun, "$thn-12-31"])
            ->get();

        $total_tahun_ini = $transaksi_tahun_ini->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->ikode_akund) {
                $carry['debit'] += $trx->jumlah;
            } elseif ($trx->rekening_kredit == $rek->kode_akun) {
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
        $data['ttd'] = Tanda_tangan::first();

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

    private function jurnal_transaksi(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl = $thn . '-' . $bln . '-' . $hari;

        $data['judul']     = 'Jurnal Transaksi';
        $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
        $data['tgl']       = Tanggal::tahun($tgl);
        $data['title']     = 'Jurnal Transaksi';
        if (!empty($data['bulan'])) {
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl']       = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        }


        $data['transaksis'] = Transaksi::with(['rekeningDebit', 'rekeningKredit', 'user'])
            ->when(!empty($data['bulan']), function ($q) use ($thn, $bln) {
                $q->whereBetween('tanggal_transaksi', [
                    "$thn-$bln-01",
                    date('Y-m-t', strtotime("$thn-$bln-01"))
                ]);
            })
            ->when(!empty($data['hari']), function ($q) use ($thn, $bln, $hari) {
                $q->whereDate('tanggal_transaksi', "$thn-$bln-$hari");
            })
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();
        $data['ttd'] = Tanda_tangan::first();
        $view = view('laporan.views.jurnal_transaksi', $data)->render();

        $pdf = Pdf::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream();
    }

    private function arus_kas(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl_awal_tahun  = "{$thn}-01-01";
        $tgl_awal_bulan  = "{$thn}-{$bln}-01";
        $tgl_akhir_bulan = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int)$bln, (int)$thn);

        $data['judul'] = 'Laporan Arus Kas';

        $data['tgl_awal_bulan'] = $tgl_awal_bulan;
        $data['tgl_akhir_bulan'] = $tgl_akhir_bulan;

        $namaBulan = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay   = date('t', strtotime("{$thn}-{$bln}-01"));

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'bulan '  . ' ' . $namaBulan . ' ' . $thn
            : 'Tahun ' . $thn;

        $data['tgl'] = $data['sub_judul'];
        $data['title'] = !empty($data['bulan'])
            ? 'Arus Kas (' . $namaBulan . ' ' . $thn . ')'
            : 'Arus Kas (Tahun ' . $thn . ')';

        // ambil arus kas dengan transaksi bulan berjalan
        $data['arus_kas'] = MasterArusKas::with([
            'child',
            'child.rek_debit.rek.transaksiDebit' => function ($q) use ($tgl_awal_bulan, $tgl_akhir_bulan) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
                    ->where('rekening_kredit', 'like', '1.1.01%');
            },
            'child.rek_kredit.rek.transaksiKredit' => function ($q) use ($tgl_awal_bulan, $tgl_akhir_bulan) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
                    ->where('rekening_debit', 'like', '1.1.01%');
            }
        ])->where('parent_id', 0)->get();
        $data['ttd'] = Tanda_tangan::first();

        // hitung saldo kas sampai akhir bulan sebelumnya
        $keuangan = new Keuangan;
        $tgl_saldo_lalu = date('Y-m-d', strtotime("-1 day", strtotime($tgl_awal_bulan)));
        $saldo_bulan_lalu = $keuangan->saldoKas($tgl_saldo_lalu);
        $data['saldo_bulan_lalu'] = $saldo_bulan_lalu;

        $view = view('laporan.views.arus_kas', $data)->render();

        $pdf = Pdf::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream();
    }

    private function laba_rugi(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = $data['bulan'] ?? null;
        $hari = $data['hari'] ?? null;

        $tgl = $thn
            . ($bln ? '-' . $bln : '-12')
            . ($hari ? '-' . $hari : '-' . date('t', strtotime("$thn-" . ($bln ?? '12') . "-01")));

        $keuangan = new Keuangan();
        $lr = $keuangan->listLabaRugi($tgl);

        $data['judul'] = 'Laporan Laba Rugi';
        $namaBulanAkhir = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay        = date('t', strtotime("{$thn}-{$bln}-01"));

        // Awal selalu 01 Januari
        $awal = '01 Januari ' . $thn;
        $akhir = $lastDay . ' ' . $namaBulanAkhir . ' ' . $thn;

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'PERIODE ' . $awal . ' S.D. ' . $akhir
            : 'TAHUN ' . $thn;

        $data['pendapatan'] = $lr['pendapatan'];
        $data['beban']      = $lr['beban'];
        $data['bp']         = $lr['bp'];
        $data['pen']        = $lr['pen'];
        $data['pendl']      = $lr['pendl'];
        $data['beb']        = $lr['beb'];
        $data['ph']         = $lr['ph'];

        $data['title'] = 'Laba Rugi';
        $data['title_bulan'] = 'Tahun ' . Tanggal::tahun($tgl);
        if (!empty($data['bulan'])) {
            $data['title_bulan'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl']       = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        }
        $data['ttd'] = Tanda_tangan::first();

        $view = view('laporan.views.laba_rugi', $data)->render();

        $pdf = Pdf::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream();
    }

    private function neraca(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        $tgl_awal  = "{$thn}-01-01";
        $tgl_akhir = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);

        $data['judul'] = 'Neraca';
        $namaBulan = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay   = date('t', strtotime("{$thn}-{$bln}-01"));

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'per ' . $lastDay . ' ' . $namaBulan . ' ' . $thn
            : 'Tahun ' . $thn;

        $data['title'] = !empty($data['bulan']) ? $data['judul'] . ' (' . $namaBulan . ' ' . $thn . ')' : $data['judul'] . ' Tahun ' . $thn;

        $data['akun1'] = AkunLevel1::where('lev1', '<=', 3)
            ->with(['akun2.akun3.rek' => function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereHas('transaksiDebit', fn($q2) => $q2->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]))
                    ->orWhereHas('transaksiKredit', fn($q2) => $q2->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]));
            }])
            ->orderBy('kode_akun', 'ASC')
            ->get();


        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['ttd'] = Tanda_tangan::first();

        $view = view('laporan.views.neraca', $data)->render();

        $pdf = Pdf::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream();
    }

    private function neraca_saldo(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl_awal  = "{$thn}-01-01";
        $tgl_akhir = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);

        $data['judul'] = 'Neraca ';

        $namaBulan = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay   = date('t', strtotime("{$thn}-{$bln}-01"));

        $data['sub_judul'] = !empty($data['bulan'])
            ? $namaBulan . ' ' . $thn
            : 'Tahun ' . $thn;

        $data['tgl'] = $data['sub_judul'];


        $data['title'] = !empty($data['bulan'])
            ? 'Neraca Saldo (' . $namaBulan . ' ' . $thn . ')'
            : 'Neraca Saldo (Tahun ' . $thn . ')';

        $data['rekening'] = Rekening::with([
            'transaksiDebit' => function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]);
            },
            'transaksiKredit' => function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]);
            }
        ])
            ->orderBy('kode_akun')
            ->get()
            ->transform(function ($rek) {
                $rek->total_debit  = $rek->transaksiDebit->sum('jumlah');
                $rek->total_kredit = $rek->transaksiKredit->sum('jumlah');
                return $rek;
            });
        $data['ttd'] = Tanda_tangan::first();

        $view = view('laporan.views.neraca_saldo', $data)->render();

        $pdf = Pdf::loadHTML($view)
        ->setPaper('a4', 'landscape')
        ->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->stream();
    }

    private function calk(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        $tgl_awal  = "{$thn}-01-01";
        $tgl_akhir = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int)$bln, (int)$thn);

        $data['judul'] = 'Calk';

        $namaBulanNormal = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $namaBulanCaps   = strtoupper($namaBulanNormal);

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'BULAN ' . $namaBulanCaps . ' TAHUN ' . $thn
            : 'TAHUN ' . $thn;

        $data['title'] = !empty($data['bulan'])
            ? $data['judul'] . ' (' . $namaBulanNormal . ' ' . $thn . ')'
            : $data['judul'] . ' Tahun ' . $thn;

        $data['profil'] = Profil::first();

        $data['akun1'] = AkunLevel1::where('lev1', '<=', 3)
            ->with(['akun2.akun3.rek'])
            ->orderBy('kode_akun', 'ASC')
            ->get();
        
        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;

        $tanggal = "{$thn}-{$bln}-01";

        $calk = Calk::where('tanggal', $tanggal)->first();

        $data['catatan'] = $calk ? $calk->catatan : '';
        $data['ttd'] = Tanda_tangan::first();

        $view = view('laporan.views.calk', $data)->render();

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
