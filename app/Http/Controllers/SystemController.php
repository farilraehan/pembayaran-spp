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
        $bulanSekarang = (int) $dateNow->format('m');
        $tahunSekarang = (int) $dateNow->format('Y');

        Transaksi::where('rekening_debit', '1.1.03.01')
            ->where('rekening_kredit', '4.1.01.01')
            ->whereNull('deleted_at')
            ->delete();

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

                if ($anggotaKelas->status !== 'aktif') continue;

                $anggotaKelasId = $anggotaKelas->id;

                // Ambil semua SPP yang statusnya B dan tanggal < bulan sekarang
                $spps = Spp::where('anggota_kelas', $anggotaKelasId)
                    ->where('status', 'B')
                    ->where(function ($q) use ($tahunSekarang, $bulanSekarang) {
                        $q->whereYear('tanggal', '<', $tahunSekarang)
                            ->orWhere(function ($q2) use ($tahunSekarang, $bulanSekarang) {
                                $q2->whereYear('tanggal', $tahunSekarang)
                                    ->whereMonth('tanggal', '<', $bulanSekarang);
                            });
                    })
                    ->orderBy('tanggal', 'asc') // supaya dari bulan terlama
                    ->get();

                foreach ($spps as $spp) {
                    // langsung create karena transaksi lama sudah dihapus
                    Transaksi::create([
                        'tanggal_transaksi' => now()->format('Y-m-d'),
                        'invoice_id' => '0',
                        'rekening_debit' => '1.1.03.01',
                        'rekening_kredit' => '4.1.01.01',
                        'spp_id' => $spp->id,
                        'siswa_id' => $value->id,
                        'jumlah' => $spp->nominal,
                        'keterangan' => 'menunggakan spp bulan ' . Tanggal::namaBulanNew((int) \Carbon\Carbon::parse($spp->tanggal)->format('m')) . ' ' . \Carbon\Carbon::parse($spp->tanggal)->format('Y'),
                        'urutan' => null,
                        'deleted_at' => null,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
        }

        echo '<script>window.close()</script>';
        exit;
    }
}
