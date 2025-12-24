<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use App\Models\Siswa;
use App\Models\Rekening;
use App\Models\Anggota_Kelas;
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
            ])
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

        return response()->json([
            'success' => true,
            'view' => view('transaksi.map_arsip.form_spp')
                ->with([
                    'siswa'         => $anggota_kelas->getSiswa,
                    'spp'           => $anggota_kelas->getSpp,
                    'spp_perbulan'  => $spp_perbulan,
                    'target_bulan'  => $target_bulan,
                    'sd_bulan_ini'  => $sd_bulan_ini,
                ])
                ->render(),
        ]);
    }
}
