<?php

namespace App\Http\Controllers;

use App\Models\Jenis_Biaya;
use Illuminate\Http\Request;

class JenisBiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jenis_Biaya::with('get_rekening');
            if ($request->has('tahun') && $request->tahun != '') {
                $data->where('angkatan', $request->tahun);
            } else {
                $data->where('angkatan', date('Y'));
            }
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('nama_akun', function ($row) {
                    return $row->get_rekening->nama_akun ?? '-';
                })
                ->addColumn('kode_akun', function ($row) {
                    return $row->get_rekening->kode_akun ?? '-';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-inline-flex gap-1">
                            <a href="/app/Jenis-biaya/'.$row->id.'/edit" class="btn btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button class="btn btn-danger btnDelete" data-id="'.$row->id.'">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->toJson();
        }
        return view('jenis_biaya.index', ['title' => 'Jenis Keuangan']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keuangan_jenis = Keuangan_jenis::get();
        $rekening = keuangan_jenis::get();

        $title = 'Tambah Nominal Keuangan';
        return view('jenis_keuangan.create', compact('title', 'keuangan_jenis','rekening'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'nama_jenis_input',
            'nama_jenis_select',
            'total_beban',
            'angkatan'
        ]);
        $rules = [
            'total_beban'       => 'required',
            'angkatan'          => 'required',
            'nama_jenis_input'  => 'nullable',
            'nama_jenis_select' => 'nullable'
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

    if ($request->filled('nama_jenis_input')) {
        $createKJ = Keuangan_jenis::create([
            'nama_jenis' => $request->nama_jenis_input
        ]);
        $keuanganJenisId = $createKJ->id;
    } elseif ($request->filled('nama_jenis_select')) {
        $keuanganJenisId = $request->nama_jenis_select;
    }

    $createKN = jenis_biaya::create([
        'angkatan'          => $request->angkatan,
        'id_keuangan_jenis' => $keuanganJenisId,
        'total_beban'       => str_replace(',', '', str_replace('.00', '', $request->total_beban)),
    ]);

    return response()->json([
        'success' => true,
        'msg' => 'Jenis Biaya berhasil disimpan',
        'biaya' => $createKN
    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jenis_Biaya $jenis_biaya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jenis_Biaya $jenis_biaya)
    {
        $keuangan_jenis = Keuangan_jenis::get();
        $jenis_biaya->load('getkeuanganJenis');

        $title = 'Edit Nominal Keuangan';

        return view('jenis_keuangan.edit', compact('title', 'keuangan_jenis','jenis_biaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jenis_Biaya $jenis_biaya)
    {
        $data = $request->only([
            'nama_jenis_select',
            'total_beban',
            'angkatan'
        ]);
        $rules = [
            'total_beban'       => 'required',
            'angkatan'          => 'required',
            'nama_jenis_select' => 'required'
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $jenis_biaya->update([
            'angkatan'          => $request->angkatan,
            'keuangan_jenis_id' => $request->nama_jenis_select,
            'total_beban'       => str_replace(',', '', str_replace('.00', '', $request->total_beban)),
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Jenis Biaya berhasil diupdate',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jenis_Biaya $jenis_biaya)
    {
        $jenis_biaya->delete();
        return response()->json([
            'success'       => true,
            'msg'           => 'Data Biaya berhasil dihapus',
            'biaya'         => $jenis_biaya
        ]);
    }
}
