<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tahun_Akademik;
use App\Models\Kelas;
use App\Models\Spp;
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
        $data['title'] = 'Laporan Pembayaran SPP';

        $data['kelas'] = !empty($data['kelas_id'])
            ? Kelas::find($data['kelas_id'])
            : null;

        $data['periode'] = [
            'awal'  => Carbon::parse($data['tgl_awal'])->locale('id'),
            'akhir' => Carbon::parse($data['tgl_akhir'])->locale('id'),
        ];

        $anggotaKelas = Anggota_Kelas::with(['getSiswa', 'getTahunAkademik'])
        ->where('status', 'aktif')
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
        ->map(function ($row) use ($data) {

            $tglAwal  = Carbon::parse($data['tgl_awal'])->startOfMonth();
            $tglAkhir = Carbon::parse($data['tgl_akhir'])->endOfMonth();

            // jumlah bulan periode
            $jumlahBulan = $tglAwal->diffInMonths($tglAkhir) + 1;

            // SPP per bulan
            $row->per_bulan = $row->getSiswa->spp_nominal ?? 0;

            // TARGET s.d saat ini
            $row->target_sd_saat_ini = $jumlahBulan * $row->per_bulan;

            // REALISASI s.d periode lalu (yg LUNAS)
            $row->sd_periode_lalu = Spp::where('anggota_kelas', $row->id)
                ->where('status', 'L')
                ->where('tanggal', '<', $tglAwal)
                ->sum('nominal');

            // REALISASI periode ini (yg LUNAS)
            $row->periode_ini = Spp::where('anggota_kelas', $row->id)
                ->where('status', 'L')
                ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                ->sum('nominal');

            // TOTAL REALISASI
            $row->sd_periode_ini = $row->sd_periode_lalu + $row->periode_ini;

            // SISA / TUNGGAKAN
            $row->sisa = $row->target_sd_saat_ini - $row->sd_periode_ini;

            return $row;
        });

        $data['anggotaKelas'] = $anggotaKelas;
        $pdf = Pdf::loadView('laporan_spp.views.pembayaran_spp', $data)
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 20,
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
