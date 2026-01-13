<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use App\Models\Siswa;
use App\Models\Rekening;
use App\Models\Jenis_Biaya;
use App\Models\Anggota_Kelas;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Utils\Tanggal;

class SppController extends Controller
{

    public function CariSiswaAktif(Request $request)
    {
        $params = $request->input('query');

        $anggota_kelas = Anggota_Kelas::select(
            'anggota_kelas.*',
            'anggota_kelas.id_siswa',
            'siswa.nama',
            'siswa.nisn',
            'siswa.alamat',
            'siswa.hp'
        )
            ->join('siswa', 'siswa.id', '=', 'anggota_kelas.id_siswa')
            ->where(function ($query) use ($params) {
                $query->where('siswa.nama', 'LIKE', "%{$params}%")
                    ->orWhere('siswa.nisn', 'LIKE', "%{$params}%");
            })
            ->where('anggota_kelas.status', 'aktif')
            ->get();

        return response()->json($anggota_kelas);
    }

    public function spp($id)
    {
        $anggota_kelas = Anggota_Kelas::where('id_siswa', $id)
            ->with([
                'getSiswa',
                'getSpp',
            ])->where('status', 'aktif')
            ->first();

        if (!$anggota_kelas) {
            return response()->json([
                'success' => false,
                'view' => '<div class="text-center text-muted py-3">Data siswa tidak ditemukan</div>'
            ]);
        }

        $spp_perbulan = $anggota_kelas->getSiswa->spp_nominal;
        $target_bulan = $anggota_kelas->getSpp->SUM('nominal');
        $sd_bulan_ini = $anggota_kelas->getSpp->where('status', 'L')->SUM('nominal');
        $sumber_dana = Rekening::where('kode_akun', 'like', '1.1.01.%')->get();
        $jenis_biaya = Rekening::where('kode_akun', 'like', '4.1.01.%')->get();
        $kode_tunggakan = Transaksi::where('rekening_debit', '1.1.03.01')
            ->where('rekening_kredit', '4.1.01.01')
            ->where('siswa_id', $anggota_kelas->getSiswa->id)
            ->orderBy('tanggal_transaksi')->where('deleted_at', null)
            ->get();

        return response()->json([
            'success' => true,
            'view' => view('transaksi.map_arsip.form_spp')
                ->with([
                    'siswa'         => $anggota_kelas->getSiswa,
                    'spp'           => $anggota_kelas->getSpp,
                    'spp_perbulan'  => $spp_perbulan,
                    'target_bulan'  => $target_bulan,
                    'sd_bulan_ini'  => $sd_bulan_ini,
                    'sumber_dana'   => $sumber_dana,
                    'jenis_biaya'   => $jenis_biaya,
                    'kode_tunggakan' => $kode_tunggakan,
                ])
                ->render(),
        ]);
    }
}
