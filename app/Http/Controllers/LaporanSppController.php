<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tahun_Akademik;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Transaksi;
use App\Models\Anggota_Kelas;
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

        $kelas = Anggota_Kelas::where('status', 'aktif')
            ->select('kode_kelas')
            ->distinct()
            ->orderBy('kode_kelas')
            ->get()
            ->map(function ($row) {
                $kelas = Kelas::where('kode_kelas', $row->kode_kelas)->first();

                return (object) [
                    'kode_kelas' => $row->kode_kelas,
                    'nama_kelas' => $kelas->nama_kelas ?? $row->kode_kelas,
                ];
            });

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
        $data['title'] = 'Laporan Pembayaran SPP';

        $data['kelas'] = !empty($data['kelas_id'])
            ? Kelas::find($data['kelas_id'])
            : null;

        $data['periode'] = [
            'awal'  => Carbon::parse($data['tgl_awal'])->locale('id'),
            'akhir' => Carbon::parse($data['tgl_akhir'])->locale('id'),
        ];

        $tglAwal  = Carbon::parse($data['tgl_awal'])->startOfMonth();
        $tglAkhir = Carbon::parse($data['tgl_akhir'])->endOfMonth();

        $anggotaKelas = Anggota_Kelas::with(['getSiswa'])
            ->when(!empty($data['kelas_id']), function ($q) use ($data) {
                $kelas = Kelas::find($data['kelas_id']);
                if ($kelas) {
                    $q->where('kode_kelas', $kelas->kode_kelas);
                }
            })
            ->when(!empty($data['tahun_akademik_id']), function ($q) use ($data) {
                $tahun = Tahun_Akademik::find($data['tahun_akademik_id']);
                if ($tahun) {
                    $q->where('tahun_akademik', $tahun->nama_tahun);
                }
            })
            ->orderBy('id')
            ->get()
            ->map(function ($row) use ($tglAwal, $tglAkhir) {

                $jumlahBulan = $tglAwal->diffInMonths($tglAkhir) + 1;

            
                $row->per_bulan = $row->getSiswa->spp_nominal ?? 0;


                $row->target_sd_saat_ini = $jumlahBulan * $row->per_bulan;

                $row->sd_periode_lalu = Spp::where('anggota_kelas', $row->id)
                    ->where('status', 'L')
                    ->where('tanggal', '<', $tglAwal)
                    ->sum('nominal');

                $row->periode_ini = Spp::where('anggota_kelas', $row->id)
                    ->where('status', 'L')
                    ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                    ->sum('nominal');

                $row->sd_periode_ini = $row->sd_periode_lalu + $row->periode_ini;

                $row->sisa = $row->target_sd_saat_ini - $row->sd_periode_ini;

                return $row;
            });

        $data['anggotaKelas'] = $anggotaKelas;

        $pdf = Pdf::loadView('laporan_spp.views.pembayaran_spp', $data)
            ->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-spp.pdf');
    }

    private function daftar_ulang(array $data)
    {
        $data['title'] = 'Laporan Pembayaran Daftar Ulang';

        $data['kelas'] = !empty($data['kelas_id'])
            ? Kelas::find($data['kelas_id'])
            : null;

        $tglAwal  = Carbon::parse($data['tgl_awal'])->startOfDay();
        $tglAkhir = Carbon::parse($data['tgl_akhir'])->endOfDay();

        $data['periode'] = [
            'awal'  => $tglAwal->locale('id'),
            'akhir' => $tglAkhir->locale('id'),
        ];

        $anggotaKelas = Anggota_Kelas::with(['getSiswa', 'getTahunAkademik'])
            ->when(!empty($data['kelas_id']), function ($q) use ($data) {
                $kelas = Kelas::find($data['kelas_id']);
                if ($kelas) {
                    $q->where('kode_kelas', $kelas->kode_kelas);
                }
            })
            ->when(!empty($data['tahun_akademik_id']), function ($q) use ($data) {
                $tahun = Tahun_Akademik::find($data['tahun_akademik_id']);
                if ($tahun) {
                    $q->where('tahun_akademik', $tahun->nama_tahun);
                }
            })
            ->orderBy('id')
            ->get()
            ->map(function ($row) use ($tglAwal, $tglAkhir) {

                $row->target = $row->getSiswa->spp_nominal ?? 0;

                $row->realisasi = Transaksi::where('siswa_id', $row->getSiswa->id ?? 0)
                    ->where('rekening_kredit', '1.1.03.02')
                    ->whereBetween('tanggal_transaksi', [$tglAwal, $tglAkhir])
                    ->sum('jumlah');

                $row->sisa = $row->target - $row->realisasi;

                return $row;
            });

        $data['anggotaKelas'] = $anggotaKelas;

        $pdf = Pdf::loadView('laporan_spp.views.daftar_ulang', $data)
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 20,
                'margin-right'  => 20,
                'enable-local-file-access' => true,
            ]);

        return $pdf->stream('laporan-daftar-ulang.pdf');
    }


}
