<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Anggota_Kelas;
use App\Models\Ruangan;
use App\Models\Jenis_Biaya;
use App\Models\Spp;
use App\Models\Tahun_akademik;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Siswa::select('id', 'nisn', 'nama', 'angkatan', 'kode_kelas');

            // Filter tahun akademik
            if ($request->tahun_akademik) {
                $tahun = explode('/', $request->tahun_akademik);
                $query->whereIn('angkatan', $tahun);
            }

            // Filter kelas
            if ($request->kelas) {
                $query->where('kode_kelas', $request->kelas);
            }

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<div class="form-check">
                                <input class="form-check-input checkItem" type="checkbox" value="' . $row->id . '">
                            </div>';
                })
                ->addColumn('action', function ($row) use ($request) {
                    $qs = http_build_query([
                        'tahun_akademik' => $request->tahun_akademik,
                        'kelas' => $request->kelas,
                    ]);
                    $detail = url("/app/siswa/{$row->id}?{$qs}");
                    $edit   = url("/app/siswa/{$row->id}/edit?{$qs}");
                    return '
                        <div class="d-inline-flex gap-1">
                            <button class="btn btn-secondary btnMutasi" id="btnMutasi"
                                    data-id="' . $row->id . '"
                                    data-tahun="' . $request->tahun_akademik . '"
                                    data-kelas="' . $request->kelas . '">
                                <i class="fa-solid fa-right-left"></i>
                            </button>
                            <a href="' . $detail . '" class="btn btn-info">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                            <a href="' . $edit . '" class="btn btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button class="btn btn-danger btnDelete"
                                data-id="' . $row->id . '"
                                data-tahun="' . $request->tahun_akademik . '"
                                data-kelas="' . $request->kelas . '">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'action'])
                ->toJson();
        }

        return view('siswa.index', ['title' => 'Data Siswa']);
    }

    public function listTahun(Request $request)
    {
        $search = $request->get('q');

        $query = Tahun_akademik::select('id', 'nama_tahun')->where('status', 'aktif');;
        if ($search) {
            $query->where('nama_tahun', 'like', "%{$search}%");
        }

        return response()->json(
            $query->get()->map(fn($item) => [
                'id'            => $item->id,
                'nama_tahun'    => $item->nama_tahun
            ])
        );
    }

    public function listKelas(Request $request)
    {
        $search = $request->get('q');

        $query = Kelas::select('id', 'nama_kelas', 'kode_kelas', 'tingkat');
        if ($search) {
            $query->where('kode_kelas', 'nama_kelas', 'tingkat', 'like', "%{$search}%");
        }

        return response()->json(
            $query->get()->map(fn($item) => [
                'id'            => $item->id,
                'nama_kelas'    => $item->nama_kelas,
                'kode_kelas'    => $item->kode_kelas,
                'tingkat'       => $item->tingkat,
            ])
        );
    }

    public function printSiswa(Request $request)
    {
        $ids = explode(',', $request->ids);
        $siswa = Siswa::whereIn('id', $ids)->get();

        $title = 'Daftar Siswa';
        $data = [
            'title' => $title,
            'siswa' => $siswa
        ];

        $pdf = Pdf::loadView('siswa.view.print', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('daftar_siswa.pdf');
    }

    public function mutasi(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'ids'   => 'required|array',
        ]);

        [$kode_kelas_baru, $tingkat_baru] = explode('-', $request->kelas);

        $year = Carbon::now()->year;
        $tahun_akademik = $year . "/" . ($year + 1);

        $tgl_masuk  = Carbon::today();
        $tgl_keluar = Carbon::today()->addYear();

        foreach ($request->ids as $id_siswa) {

            $siswa = Siswa::where('id', $id_siswa)->first();
            if (!$siswa) continue;

            $nominal_spp = $siswa->spp_nominal;
            $anggota = Anggota_Kelas::where('id_siswa', $id_siswa)
                ->orderBy('id', 'DESC')
                ->first();

            //UPDATE kelas saja
            if ($anggota && $anggota->tingkat == $tingkat_baru) {
                $anggota->update([
                    'kode_kelas' => $kode_kelas_baru,
                ]);
                continue;
            }

            //NONAKTIFKAN anggota_kelas lama
            if ($anggota) {
                $anggota->update([
                    'status' => 'nonaktif'
                ]);
            }

            //BUAT anggota_kelas baru
            $anggotaBaru = Anggota_Kelas::create([
                'id_siswa'          => $id_siswa,
                'tahun_akademik'    => $tahun_akademik,
                'tingkat'           => $tingkat_baru,
                'kode_kelas'        => $kode_kelas_baru,
                'tgl_masuk'         => $tgl_masuk->format('Y-m-d'),
                'tgl_keluar'        => $tgl_keluar->format('Y-m-d'),
                'status'            => 'aktif',
            ]);

            //BUAT SPP baru
            $anggota_kelas_id = $anggotaBaru->id;
            $awal  = $tgl_masuk->copy()->startOfMonth();
            $akhir = $awal->copy()->addYear()->subMonth();

            while ($awal->lte($akhir)) {
                Spp::create([
                    'tanggal'       => $awal->format('Y-m-d'),
                    'anggota_kelas' => $anggota_kelas_id,
                    'nominal'       => $nominal_spp,
                ]);
                $awal->addMonth();
            }
        }

        return response()->json([
            'success' => true,
            'msg' => "Mutasi berhasil diproses!"
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title          = "Tambah Siswa";
        $kelas          = Kelas::get();
        $ruang          = Ruangan::get();
        $jurusan        = Jurusan::get();
        $tahunAkademmik = Tahun_akademik::get();
        $jenisBiaya     = Jenis_Biaya::where('kode_akun', '1.1.03.01')->where('angkatan', date('Y'))->first();

        return view('siswa.create', compact('title', 'kelas', 'jurusan', 'jenisBiaya', 'ruang', 'tahunAkademmik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'nipd',
            'nisn',
            'nik',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'kecamatan',
            'kelurahan',
            'dusun',
            'rt',
            'rw',
            'alamat',
            'kode_pos',
            'status_awal',
            'status_siswa',
            'tahun_akademik',
            'foto',
            'kebutuhan_khusus',
            'jenis_tinggal',
            'transportasi',
            'hp',
            'kelas',
            'password',
            'jurusan',
            'angkatan',
            'skhun',
            'penerima_kps',
            'no_kps',
            'alokasi_spp',
            'email',
            'ruangan',
            'tanggal_masuk',
            'nama_ayah',
            'tahun_lahir_ayah',
            'pendidikan_ayah',
            'pekerjaan_ayah',
            'penghasilan_ayah',
            'kebutuhan_khusus_ayah',
            'no_telp_ayah',
            'nama_ibu',
            'tahun_lahir_ibu',
            'pendidikan_ibu',
            'pekerjaan_ibu',
            'penghasilan_ibu',
            'kebutuhan_khusus_ibu',
            'no_telp_ibu',
            'nama_wali',
            'tahun_lahir_wali',
            'pendidikan_wali',
            'pekerjaan_wali',
            'penghasilan_wali',
            'kebutuhan_khusus_wali',
            'no_telp_wali'
        ]);

        $rules = [
            'nipd'                  => 'required',
            'nisn'                  => 'required',
            'tahun_akademik'        => 'required',
            'nik'                   => 'required',
            'nama'                  => 'required',
            'tempat_lahir'          => 'required',
            'tanggal_lahir'         => 'required|date',
            'jenis_kelamin'         => 'required',
            'agama'                 => 'required',
            'kecamatan'             => 'required',
            'kelurahan'             => 'required',
            'tanggal_masuk'         => 'required',
            'ruangan'               => 'required',
            'email'                 => 'required',
            'dusun'                 => 'required',
            'rt'                    => 'required',
            'rw'                    => 'required',
            'password'              => 'required',
            'alamat'                => 'required',
            'kode_pos'              => 'required',
            'status_awal'           => 'required',
            'status_siswa'          => 'required',
            'foto'                  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kebutuhan_khusus'      => 'required',
            'jenis_tinggal'         => 'required',
            'transportasi'          => 'required',
            'hp'                    => 'required',
            'kelas'                 => 'required',
            'jurusan'               => 'required',
            'angkatan'              => 'required',
            'skhun'                 => 'required',
            'penerima_kps'          => 'required',
            'no_kps'                => 'nullable',
            'alokasi_spp'           => 'required',
            'nama_ayah'             => 'required',
            'tahun_lahir_ayah'      => 'required',
            'pendidikan_ayah'       => 'required',
            'pekerjaan_ayah'        => 'required',
            'penghasilan_ayah'      => 'required',
            'kebutuhan_khusus_ayah' => 'required',
            'no_telp_ayah'          => 'required',
            'nama_ibu'              => 'required',
            'tahun_lahir_ibu'       => 'required',
            'pendidikan_ibu'        => 'required',
            'pekerjaan_ibu'         => 'required',
            'penghasilan_ibu'       => 'required',
            'kebutuhan_khusus_ibu'  => 'required',
            'no_telp_ibu'           => 'required',
            'nama_wali'             => 'nullable',
            'tahun_lahir_wali'      => 'nullable',
            'pendidikan_wali'       => 'nullable',
            'pekerjaan_wali'        => 'nullable',
            'penghasilan_wali'      => 'nullable',
            'kebutuhan_khusus_wali' => 'nullable',
            'no_telp_wali'          => 'nullable',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $fileName = 'default.png';
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $fileName = time() . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('siswa', $fileName, 'public');
        }
        $data['foto'] = $fileName;

        $kelas = $request->kelas;
        list($kodeKls, $tingkat) = explode('-', $kelas);

        $create = Siswa::create([
            'nipd'                  => $request->nipd,
            'nisn'                  => $request->nisn,
            'password'              => $request->password,
            'nik'                   => $request->nik,
            'email'                 => $request->email,
            'tahun_akademik'        => $request->tahun_akademik,
            'tanggal_masuk'         => $request->tanggal_masuk,
            'tingkat'               => $tingkat,
            'ruang'                 => $request->ruangan,
            'id_user'               => Auth::user()->id,
            'nama'                  => $request->nama,
            'tempat_lahir'          => $request->tempat_lahir,
            'tanggal_lahir'         => $request->tanggal_lahir,
            'jenis_kelamin'         => $request->jenis_kelamin,
            'agama'                 => $request->agama,
            'kecamatan'             => $request->kecamatan,
            'kelurahan'             => $request->kelurahan,
            'dusun'                 => $request->dusun,
            'rt'                    => $request->rt,
            'rw'                    => $request->rw,
            'alamat'                => $request->alamat,
            'kode_pos'              => $request->kode_pos,
            'status_awal'           => $request->status_awal,
            'status_siswa'          => $request->status_siswa,
            'foto'                  => $fileName,
            'kebutuhan_khusus'      => $request->kebutuhan_khusus,
            'jenis_tinggal'         => $request->jenis_tinggal,
            'alat_transportasi'     => $request->transportasi,
            'hp'                    => $request->hp,
            'kode_kelas'            => $kodeKls,
            'kode_jurusan'          => $request->jurusan,
            'angkatan'              => $request->angkatan,
            'skhun'                 => $request->skhun,
            'penerima_kps'          => $request->penerima_kps,
            'no_kps'                => $request->no_kps,
            'spp_nominal'           => str_replace(',', '', str_replace('.00', '', $request->alokasi_spp)),
            'nama_ayah'             => $request->nama_ayah,
            'tahun_lahir_ayah'      => $request->tahun_lahir_ayah,
            'pendidikan_ayah'       => $request->pendidikan_ayah,
            'pekerjaan_ayah'        => $request->pekerjaan_ayah,
            'penghasilan_ayah'      => $request->penghasilan_ayah,
            'kebutuhan_khusus_ayah' => $request->kebutuhan_khusus_ayah,
            'no_telepon_ayah'       => $request->no_telp_ayah,
            'nama_ibu'              => $request->nama_ibu,
            'tahun_lahir_ibu'       => $request->tahun_lahir_ibu,
            'pendidikan_ibu'        => $request->pendidikan_ibu,
            'pekerjaan_ibu'         => $request->pekerjaan_ibu,
            'penghasilan_ibu'       => $request->penghasilan_ibu,
            'kebutuhan_khusus_ibu'  => $request->kebutuhan_khusus_ibu,
            'no_telepon_ibu'        => $request->no_telp_ibu,
            'nama_wali'             => $request->nama_wali,
            'tahun_lahir_wali'      => $request->tahun_lahir_wali,
            'pendidikan_wali'       => $request->pendidikan_wali,
            'pekerjaan_wali'        => $request->pekerjaan_wali,
            'penghasilan_wali'      => $request->penghasilan_wali,
            'kebutuhan_khusus_wali' => $request->kebutuhan_khusus_wali,
            'no_telepon_wali'       => $request->no_telp_wali,
            'id_keuangan_jenis'     => $keuanganJenisId ?? null,
        ]);

        $anggota_kelas = Anggota_Kelas::create([
            'id_siswa'          => $create->id,
            'tahun_akademik' => $request->tahun_akademik,
            'tingkat'           => $tingkat,
            'kode_kelas'        => $kodeKls,
            'tgl_masuk'         => $request->tanggal_masuk,
            'tgl_keluar'        => Carbon::parse($request->tanggal_masuk)->addYear()->format('Y-m-d'),
            'status'            => 'aktif',
        ]);

        $mulai = Carbon::parse($request->tanggal_masuk)->startOfMonth();
        $akhir = $mulai->copy()->addYear()->subMonth();

        while ($mulai->lte($akhir)) {
            Spp::create([
                'tanggal'       => $mulai->format('Y-m-d'),
                'anggota_kelas' => $anggota_kelas->id,
                'nominal'       => str_replace(',', '', str_replace('.00', '', $request->alokasi_spp)),
            ]);

            $mulai->addMonth();
        }

        return response()->json([
            'success' => true,
            'msg' => 'Siswa berhasil disimpan',
            'data' => $create
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $title = "Detail Siswa";
        $siswa = Siswa::where('id', $siswa->id)->first();

        return view('siswa.detail', compact('title', 'siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function  edit(Siswa $siswa)
    {
        $title          = "Edit Siswa";
        $kelas          = Kelas::get();
        $ruang          = Ruangan::get();
        $jurusan        = Jurusan::get();
        $tahunAkademmik = Tahun_akademik::get();
        $jenisBiaya     = Jenis_Biaya::where('kode_akun', '1.1.03.01')->where('angkatan', date('Y'))->first();

        return view('siswa.edit', compact('title', 'kelas', 'jurusan', 'jenisBiaya', 'siswa', 'ruang', 'tahunAkademmik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->only([
            'nipd',
            'nisn',
            'nik',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'kecamatan',
            'kelurahan',
            'dusun',
            'rt',
            'rw',
            'alamat',
            'kode_pos',
            'status_awal',
            'status_siswa',
            'foto',
            'kebutuhan_khusus',
            'jenis_tinggal',
            'transportasi',
            'hp',
            'kelas',
            'password',
            'jurusan',
            'angkatan',
            'skhun',
            'penerima_kps',
            'no_kps',
            'spp_nominal',
            'email',
            'ruangan',
            'tanggal_masuk',
            'nama_ayah',
            'tahun_lahir_ayah',
            'pendidikan_ayah',
            'pekerjaan_ayah',
            'penghasilan_ayah',
            'kebutuhan_khusus_ayah',
            'no_telp_ayah',
            'nama_ibu',
            'tahun_lahir_ibu',
            'pendidikan_ibu',
            'pekerjaan_ibu',
            'penghasilan_ibu',
            'kebutuhan_khusus_ibu',
            'no_telp_ibu',
            'nama_wali',
            'tahun_lahir_wali',
            'pendidikan_wali',
            'pekerjaan_wali',
            'penghasilan_wali',
            'kebutuhan_khusus_wali',
            'no_telp_wali'
        ]);

        $rules = [
            'nipd'                  => 'required',
            'nisn'                  => 'required',
            'nik'                   => 'required',
            'nama'                  => 'required',
            'tempat_lahir'          => 'required',
            'tanggal_lahir'         => 'required|date',
            'jenis_kelamin'         => 'required',
            'agama'                 => 'required',
            'kecamatan'             => 'required',
            'kelurahan'             => 'required',
            'dusun'                 => 'required',
            'rt'                    => 'required',
            'rw'                    => 'required',
            'password'              => 'required',
            'alamat'                => 'required',
            'tanggal_masuk'         => 'required',
            'email'                 => 'required',
            'kode_pos'              => 'required',
            'status_awal'           => 'required',
            'status_siswa'          => 'required',
            'foto'                  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kebutuhan_khusus'      => 'required',
            'jenis_tinggal'         => 'required',
            'transportasi'          => 'required',
            'hp'                    => 'required',
            'kelas'                 => 'required',
            'jurusan'               => 'required',
            'angkatan'              => 'required',
            'skhun'                 => 'required',
            'penerima_kps'          => 'required',
            'no_kps'                => 'nullable',
            'spp_nominal'           => 'required',
            'nama_ayah'             => 'required',
            'tahun_lahir_ayah'      => 'required',
            'pendidikan_ayah'       => 'required',
            'pekerjaan_ayah'        => 'required',
            'penghasilan_ayah'      => 'required',
            'kebutuhan_khusus_ayah' => 'required',
            'no_telp_ayah'          => 'required',
            'nama_ibu'              => 'required',
            'tahun_lahir_ibu'       => 'required',
            'pendidikan_ibu'        => 'required',
            'pekerjaan_ibu'         => 'required',
            'penghasilan_ibu'       => 'required',
            'kebutuhan_khusus_ibu'  => 'required',
            'no_telp_ibu'           => 'required',
            'nama_wali'             => 'nullable',
            'tahun_lahir_wali'      => 'nullable',
            'pendidikan_wali'       => 'nullable',
            'pekerjaan_wali'        => 'nullable',
            'penghasilan_wali'      => 'nullable',
            'kebutuhan_khusus_wali' => 'nullable',
            'no_telp_wali'          => 'nullable',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $fileName = 'default.png';

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $fileName = time() . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('siswa', $fileName, 'public');
        }
        $data['foto'] = $fileName;

        $kelas = $request->kelas;
        list($kodeKls, $tingkat) = explode('-', $kelas);

        $siswa->update([
            'nipd'                  => $request->nipd,
            'nisn'                  => $request->nisn,
            'password'              => $request->password,
            'nik'                   => $request->nik,
            'email'                 => $request->email,
            'tingkat'               => $tingkat,
            'tanggal_masuk'         => $request->tanggal_masuk,
            'ruang'                 => $request->ruangan,
            'id_user'               => Auth::user()->id,
            'nama'                  => $request->nama,
            'tempat_lahir'          => $request->tempat_lahir,
            'tanggal_lahir'         => $request->tanggal_lahir,
            'jenis_kelamin'         => $request->jenis_kelamin,
            'agama'                 => $request->agama,
            'kecamatan'             => $request->kecamatan,
            'kelurahan'             => $request->kelurahan,
            'dusun'                 => $request->dusun,
            'rt'                    => $request->rt,
            'rw'                    => $request->rw,
            'alamat'                => $request->alamat,
            'kode_pos'              => $request->kode_pos,
            'status_awal'           => $request->status_awal,
            'status_siswa'          => $request->status_siswa,
            'foto'                  => $fileName,
            'kebutuhan_khusus'      => $request->kebutuhan_khusus,
            'jenis_tinggal'         => $request->jenis_tinggal,
            'alat_transportasi'     => $request->transportasi,
            'hp'                    => $request->hp,
            'kode_kelas'            => $kodeKls,
            'kode_jurusan'          => $request->jurusan,
            'angkatan'              => $request->angkatan,
            'skhun'                 => $request->skhun,
            'penerima_kps'          => $request->penerima_kps,
            'no_kps'                => $request->no_kps,
            'spp_nominal'           => str_replace(',', '', str_replace('.00', '', $request->spp_nominal)),
            'nama_ayah'             => $request->nama_ayah,
            'tahun_lahir_ayah'      => $request->tahun_lahir_ayah,
            'pendidikan_ayah'       => $request->pendidikan_ayah,
            'pekerjaan_ayah'        => $request->pekerjaan_ayah,
            'penghasilan_ayah'      => $request->penghasilan_ayah,
            'kebutuhan_khusus_ayah' => $request->kebutuhan_khusus_ayah,
            'no_telepon_ayah'       => $request->no_telp_ayah,
            'nama_ibu'              => $request->nama_ibu,
            'tahun_lahir_ibu'       => $request->tahun_lahir_ibu,
            'pendidikan_ibu'        => $request->pendidikan_ibu,
            'pekerjaan_ibu'         => $request->pekerjaan_ibu,
            'penghasilan_ibu'       => $request->penghasilan_ibu,
            'kebutuhan_khusus_ibu'  => $request->kebutuhan_khusus_ibu,
            'no_telepon_ibu'        => $request->no_telp_ibu,
            'nama_wali'             => $request->nama_wali,
            'tahun_lahir_wali'      => $request->tahun_lahir_wali,
            'pendidikan_wali'       => $request->pendidikan_wali,
            'pekerjaan_wali'        => $request->pekerjaan_wali,
            'penghasilan_wali'      => $request->penghasilan_wali,
            'kebutuhan_khusus_wali' => $request->kebutuhan_khusus_wali,
            'no_telepon_wali'       => $request->no_telp_wali,
            'id_keuangan_jenis'     => $keuanganJenisId ?? null,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Siswa berhasil diupdate',
            'data' => $siswa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return response()->json([
            'success'       => true,
            'msg'           => 'Data Siswa berhasil dihapus',
            'siswa'         => $siswa
        ]);
    }
}
