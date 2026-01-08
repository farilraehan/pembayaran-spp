<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Jenis_transaksi;
use App\Models\Profil;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Inventaris;
use App\Utils\UtilsInventaris;
use App\Utils\Tanggal;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Keuangan;



class TransaksiController extends Controller
{
    /**
     * Jurnal Umum.
     */
    public function index()
    {
        $title = 'Jurnal Umum';
        $jenisTransaksi = Jenis_transaksi::all();
        $rekening = Rekening::orderBy('kode_akun', 'asc')->get();

        return view('transaksi.index', compact('title', 'jenisTransaksi', 'rekening'));
    }

    /**
     * Daftar Inventaris Jurnal Umum.
     */
    public function daftarInventaris()
    {
        $tanggal = request()->get('tanggal');
        $jenis = request()->get('jenis');
        $kategori = request()->get('kategori');

        $inventaris = Inventaris::where([
            ['jenis', $jenis],
            ['kategori', intval($kategori)],
            ['tanggal_beli', '<=', $tanggal],
        ])->where(function ($query) {
            $query->where('status', 'baik')->orwhere('status', 'busak');
        })->get();

        $inventarisArray = $inventaris->toArray();
        foreach ($inventaris as $index => $inv) {
            $nilaiBuku = UtilsInventaris::nilaiBuku($tanggal, $inv);

            $inventarisArray[$index]['nilai_buku'] = $nilaiBuku;
        }

        return response()->json($inventarisArray);
    }

    /**
     * Store Jurnal Umum.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'transaksi',
            "tanggal",
            "sumber_dana",
            "disimpan_ke",
            "jurnal_umum",
            "beli_inventaris",
            "hapus_inventaris"
        ]);

        if (!empty($data['sumber_dana'])) {
            $data['sumber_dana'] = explode('. ', $data['sumber_dana'], 2)[0];
        }

        if (!empty($data['disimpan_ke'])) {
            $data['disimpan_ke'] = explode('. ', $data['disimpan_ke'], 2)[0];
        }

        $request->validate([
            'transaksi' => 'required',
            'tanggal' => 'required',
            'sumber_dana' => 'required',
            'disimpan_ke' => 'required',
            'jurnal_umum' => 'required|array',
            'beli_inventaris' => 'required|array',
            'hapus_inventaris' => 'required|array',
        ]);

        $message = "Transaksi berhasil disimpan.";
        $form = $data[$data['transaksi']];
        if ($data['transaksi'] == 'jurnal_umum') {
            Transaksi::create([
                'user_id' => auth()->user()->id,
                'tanggal_transaksi' => $data['tanggal'],
                'rekening_debit' => $data['disimpan_ke'],
                'rekening_kredit' => $data['sumber_dana'],
                'keterangan' => $form['keterangan'],
                'jumlah' => floatval(str_replace(',', '', $form['nominal'])),
            ]);
        }

        if ($data['transaksi'] == 'beli_inventaris') {
            $jenis_inventaris = $form['jenis_inventaris'];
            $kategori_inventaris = $form['kategori_inventaris'];
            $nama_barang = $form['nama_barang'];
            $harga_satuan = floatval(str_replace(',', '', $form['harga_satuan']));
            $umur_ekonomis = $form['umur_ekonomis'];
            $jumlah_unit = $form['jumlah_unit'];
            $harga_perolehan = $harga_satuan * $jumlah_unit;

            Inventaris::create([
                'nama' => $nama_barang,
                'tanggal_beli' => $data['tanggal'],
                'tanggal_validasi' => $data['tanggal'],
                'jumlah' => $jumlah_unit,
                'harga_satuan' => $harga_satuan,
                'umur_ekonomis' => $umur_ekonomis,
                'jenis' => $jenis_inventaris,
                'kategori' => $kategori_inventaris,
                'status' => 'baik',
            ]);

            $keterangan = "Beli " . $jumlah_unit . " unit " . $nama_barang;
            Transaksi::create([
                'user_id' => auth()->user()->id,
                'tanggal_transaksi' => $data['tanggal'],
                'rekening_debit' => $data['sumber_dana'],
                'rekening_kredit' => $data['disimpan_ke'],
                'keterangan' => $keterangan,
                'jumlah' => $harga_perolehan,
            ]);
        }

        if ($data['transaksi'] == 'hapus_inventaris') {
            $nama_barang = explode('#', $form['daftar_barang']);
            $id_inv = $nama_barang[0];
            $jumlah_barang = $nama_barang[1];
            $status = $form['alasan'];
            $jumlah_unit = $form['jumlah_unit_inventaris'];
            $nilai_buku = floatval(str_replace(',', '', $form['nilai_buku']));
            $harga_jual = floatval(str_replace(',', '', $form['harga_jual']));

            $inv = Inventaris::where('id', $id_inv)->first();

            $tanggal_beli = $inv->tanggal_beli;
            $harga_satuan = $inv->harga_satuan;
            $umur_ekonomis = $inv->umur_ekonomis;
            $sisa_unit = $jumlah_barang - $jumlah_unit;
            $barang = $inv->nama;
            $jenis = $inv->jenis;
            $kategori = $inv->kategori;

            $trx_penghapusan = [
                'user_id' => auth()->user()->id,
                'mitra_id' => '0',
                'po_id' => '0',
                'tanggal_transaksi' => $data['tanggal'],
                'rekening_debit' => $data['disimpan_ke'],
                'rekening_kredit' => $data['sumber_dana'],
                'keterangan' => 'Penghapusan ' . $jumlah_unit . ' unit ' . $barang . ' (' . $id_inv . ')' . ' karena ' . $status,
                'jumlah' => $nilai_buku,
                'urutan' => '0',
            ];

            $update_inventaris = [
                'jumlah' => $sisa_unit,
                'tanggal_validasi' => $data['tanggal']
            ];

            $update_status_inventaris = [
                'status' => $status,
                'tanggal_validasi' => $data['tanggal']
            ];

            $insert_inventaris = [
                'nama' => $barang,
                'tanggal_beli' => $tanggal_beli,
                'jumlah' => $jumlah_unit,
                'harga_satuan' => $harga_satuan,
                'umur_ekonomis' => $umur_ekonomis,
                'jenis' => $jenis,
                'kategori' => $kategori,
                'status' => $status,
                'tanggal_validasi' => $data['tanggal'],
            ];

            $trx_penjualan = [
                'user_id' => auth()->user()->id,
                'mitra_id' => '0',
                'po_id' => '0',
                'tgl_transaksi' => $data['tanggal'],
                'rekening_debit' => '1',
                'rekening_kredit' => '55',
                'keterangan_transaksi' => 'Penjualan ' . $jumlah_unit . ' unit ' . $barang . ' (' . $id_inv . ')',
                'jumlah' => $harga_jual,
                'urutan' => '0',
            ];

            if ($status != 'rusak') {
                $transaksi = Transaksi::create($trx_penghapusan);
            }

            if ($jumlah_unit < $jumlah_barang) {
                Inventaris::where('id', $id_inv)->update($update_inventaris);
                if ($status != 'revaluasi') {
                    Inventaris::create($insert_inventaris);
                }
            } else {
                Inventaris::where('id', $id_inv)->update($update_status_inventaris);
            }

            if ($status == 'revaluasi') {
                $harga_jual = floatval(str_replace(',', '', str_replace('.00', '', $request->harga_jual)));

                $insert_inventaris_baru = [
                    'nama' => $barang,
                    'tanggal_beli' => $data['tanggal'],
                    'tanggal_validasi' => $data['tanggal'],
                    'jumlah' => $jumlah_unit,
                    'harga_satuan' => $harga_jual / $jumlah_unit,
                    'umur_ekonomis' => $umur_ekonomis,
                    'jenis' => $jenis,
                    'kategori' => $kategori,
                    'status' => 'baik',
                ];

                if ($harga_jual != $nilai_buku) {
                    $jumlah = $harga_jual - $nilai_buku;
                    $trx_revaluasi = [
                        'user_id' => auth()->user()->id,
                        'mitra_id' => '0',
                        'po_id' => '0',
                        'tgl_transaksi' => $data['tanggal'],
                        'rekening_debit' => '1',
                        'rekening_kredit' => '57',
                        'keterangan_transaksi' => 'Revaluasi ' . $jumlah_unit . ' unit ' . $barang . ' (' . $id_inv . ')',
                        'jumlah' => $jumlah,
                        'urutan' => '0',
                    ];

                    Transaksi::create($trx_revaluasi);
                }

                Inventaris::create($insert_inventaris_baru);
            }

            $message = 'Penghapusan ' . $jumlah_unit . ' unit ' . $barang . ' karena ' . $status;
            if ($status == 'dijual') {
                $transaksi = Transaksi::create($trx_penjualan);
                $message = 'Penjualan ' . $jumlah_unit . ' unit ' . $barang;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Detail jurnal umum.
     */
    public function show(Transaksi $Transaksi)
    {
        //
    }

    /**
     * Remove detail jurnal umum
     */
    public function destroy(Transaksi $Transaksi)
    {
        //
    }

    //PEMBAYARAN SPP
    public function pembayaranSPP()
    {
        $title = 'Tagihan Siswa';

        return view('transaksi.pembayaran-spp', compact('title'));
    }

    /**
     * Store PEMBAYARAN SPP
     */
    public function pembayaranSPPStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'siswa_id' => 'required',
            'sumber_dana' => 'required',
            'jenis_biaya' => 'required',
            'keterangan' => 'required',
            'spp_id' => 'required_if:jenis_biaya,4.1.01.01|array|min:1',
            'nominal_spp' => 'required_if:jenis_biaya,4.1.01.01|array',
        ]);

        $sppIds   = $request->input('spp_id', []);
        $nominals = $request->input('nominal_spp', []);

        $transaksiList = [];
        $detailSpp = [];
        $total = 0;

        if ($request->jenis_biaya === '4.1.01.01') {

            if (count($sppIds) !== count($nominals)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Data SPP tidak sinkron'
                ], 422);
            }

            foreach ($sppIds as $i => $sppId) {

                $nilai = (int) str_replace(['.', ','], '', $nominals[$i]);
                $total += $nilai;

                $transaksi = Transaksi::create([
                    'tanggal_transaksi' => $request->tanggal,
                    'invoice_id' => '0',
                    'rekening_debit' => $request->sumber_dana,
                    'rekening_kredit' => $request->jenis_biaya,
                    'spp_id' => $sppId,
                    'siswa_id' => $request->siswa_id,
                    'jumlah' => $nilai,
                    'keterangan' => $request->keterangan . '(' . Tanggal::NamaBulan(Spp::find($sppId)->tanggal) . Tanggal::tahun(Spp::find($sppId)->tanggal) . ')',
                    'urutan' => null,
                    'deleted_at' => null,
                    'user_id' => auth()->user()->id,
                ]);

                Spp::where('id', $sppId)->update(['status' => 'L']);

                $spp = Spp::find($sppId);
                $detailSpp[] = [
                    'bulan' => \App\Utils\Tanggal::NamaBulan($spp->tanggal),
                    'tanggal' => $spp->tanggal,
                    'nominal' => $nilai,
                ];

                $transaksiList[] = $transaksi;
            }
        } else {
            $transaksi = Transaksi::create([
                'tanggal_transaksi' => $request->tanggal,
                'invoice_id' => '0',
                'rekening_debit' => $request->sumber_dana,
                'rekening_kredit' => $request->jenis_biaya,
                'spp_id' => '0',
                'siswa_id' => $request->siswa_id,
                'jumlah' => str_replace(',', '', str_replace('.00', '', $request->nominal)),
                'keterangan' => $request->keterangan,
                'urutan' => null,
                'deleted_at' => null,
                'user_id' => auth()->user()->id,
            ]);

            $transaksiList[] = $transaksi;
        }

        return response()->json([
            'success' => true,
            'msg' => 'Pembayaran berhasil disimpan',
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'id_transaksi' => collect($transaksiList)->pluck('id')->toArray(),
            'detail_spp' => $detailSpp,
        ]);
    }

    /**
     * Detail PEMBAYARAN SPP
     */
    public function pembayaranSPPDetail($id)
    {
        $siswa = Siswa::with([
            'getKelas',
            'getTransaksi' => function ($q) {
                $q->whereNull('deleted_at')
                    ->orderByDesc('id')
                    ->with('spp');
            }
        ])->findOrFail($id);

        return view('transaksi.map_arsip.detail', compact('siswa'));
    }
    public function pembayaranSPPPrintAll($id)
    {
        $siswa = Siswa::with([
            'getKelas',
            'getTransaksi' => function ($q) {
                $q->whereNull('deleted_at')
                    ->orderByDesc('id')
                    ->with('spp');
            }
        ])->findOrFail($id);

        return view('transaksi.map_arsip.detail_cetak', compact('siswa'));
    }

    /**
     * Print PEMBAYARAN SPP
     */
    public function pembayaranSPPPrint(Request $request)
    {
        $ids = explode(',', $request->query('ids'));

        $transaksis = Transaksi::with('siswa')
            ->whereIn('id', $ids)
            ->get();

        if ($transaksis->isEmpty()) {
            abort(404);
        }

        $header = $transaksis->first();
        $lembaga = Profil::first()->nama;
        $allSpps = collect();

        foreach ($transaksis as $transaksi) {
            $rawSpp = $transaksi->spp_id;
            if (is_string($rawSpp)) {
                $decoded = json_decode($rawSpp, true);
                $sppIds = is_array($decoded) ? $decoded : [$rawSpp];
            } elseif (is_numeric($rawSpp)) {
                $sppIds = [$rawSpp];
            } else {
                $sppIds = [];
            }

            $spps = Spp::whereIn('id', $sppIds)->get();
            $allSpps = $allSpps->merge($spps);
        }

        $data = [
            'title'         => 'Kwitansi Pembayaran SPP',
            'header'        => $header,
            'spps'          => $allSpps,
            'transaksis'    => $transaksis,
            'nama_lembaga'  => $lembaga,
        ];
        $logoPath = public_path('assets/img/apple-icon.png');
        if (file_exists($logoPath)) {
            $data['logo'] = base64_encode(file_get_contents($logoPath));
            $data['logo_type'] = pathinfo($logoPath, PATHINFO_EXTENSION);
        }
        $pdf = Pdf::loadView('transaksi.map_arsip.view.kwitansi_spp', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('kwitansi_spp.pdf');
    }

    public function printAllSelected(Request $request)
    {
        $ids = explode(',', $request->query('ids'));

        $transaksis = Transaksi::with('siswa')
            ->whereIn('id', $ids)
            ->get();

        if ($transaksis->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        $header = $transaksis->first();

        $lembaga = Profil::first()->nama;
        $data = [
            'title'        => 'Riwayat Pembayaran',
            'header'       => $header,
            'transaksis'   => $transaksis,
            'nama_lembaga' => $lembaga,
        ];
        $logoPath = public_path('assets/img/apple-icon.png');
        if (file_exists($logoPath)) {
            $data['logo'] = base64_encode(file_get_contents($logoPath));
            $data['logo_type'] = pathinfo($logoPath, PATHINFO_EXTENSION);
        }
        $pdf = Pdf::loadView('transaksi.map_arsip.view.cetak', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('cetak.pdf');
    }

    public function CetakPadaKartu(Request $request)
    {
        $ids = explode(',', $request->query('ids'));
        $transaksis = Transaksi::with('siswa', 'spp')
            ->whereIn('id', $ids)
            ->get();

        if ($transaksis->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        return view('transaksi.map_arsip.view.cetakPadaKartu', [
            'transaksis' => $transaksis
        ]);
    }

    /**
     * Remove PEMBAYARAN SPP
     */
    public function pembayaranSPPDestroy(Transaksi $Transaksi)
    {
        $sppId = $Transaksi->spp_id;
        if ($sppId && is_numeric($sppId)) {
            Spp::where('id', $sppId)->update(['status' => 'B']);
        }

        $Transaksi->update(['deleted_at' => now()]);
        return response()->json([
            'success' => true,
            'msg' => 'Transaksi pembayaran SPP berhasil dihapus.'
        ]);
    }
}
