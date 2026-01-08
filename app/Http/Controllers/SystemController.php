<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use App\Models\Siswa;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\Jenis_Biaya;
use App\Models\Anggota_Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Utils\Tanggal;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function GenerateTunggakan($waktu)
    {
        $dateNow = Carbon::createFromTimestamp($waktu);
        $dateLastMonth = $dateNow->copy()->subMonth();

        $bulanLalu = (int) $dateLastMonth->format('m');
        $tahunLalu = (int) $dateLastMonth->format('Y');

        $siswa = Siswa::whereHas('getAnggotaKelas', function ($q) {
            $q->where('status', 'aktif');
        })
            ->with([
                'getAnggotaKelas' => function ($q) {
                    $q->where('status', 'aktif');
                },
                'getAnggotaKelas.getSpp'
            ])
            ->get();

        foreach ($siswa as $value) {

            foreach ($value->getAnggotaKelas as $anggotaKelas) {

                if ($anggotaKelas->status !== 'aktif') {
                    continue;
                }

                $anggotaKelasId = $anggotaKelas->id;

                $spp = Spp::where('anggota_kelas', $anggotaKelasId)
                    ->whereMonth('tanggal', $bulanLalu)
                    ->whereYear('tanggal', $tahunLalu)
                    ->first();

                if ($spp && $spp->status === 'B') {

                    $cekTransaksi = Transaksi::where('spp_id', $spp->id)
                        ->whereMonth('tanggal_transaksi', now()->month)
                        ->whereYear('tanggal_transaksi', now()->year)
                        ->exists();

                    if (!$cekTransaksi) {
                        Transaksi::create([
                            'tanggal_transaksi' => now()->format('Y-m-d'),
                            'invoice_id' => '0',
                            'rekening_debit' => '1.1.03.01',
                            'rekening_kredit' => '4.1.01.01',
                            'spp_id' => $spp->id,
                            'siswa_id' => $value->id,
                            'jumlah' => $spp->nominal,
                            'keterangan' => 'menunggakan spp bulan ' . $bulanLalu . ' ' . $tahunLalu,
                            'urutan' => null,
                            'deleted_at' => null,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }
            }
        }
        echo '<script>window.close()</script>';
        exit;
    }
}
