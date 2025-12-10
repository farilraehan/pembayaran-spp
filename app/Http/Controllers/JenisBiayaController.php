<?php

namespace App\Http\Controllers;

use App\Models\Jenis_Biaya;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $Rekening = Rekening::where('kode_akun', 'like', '1.1.03.%')->get();
        $title = 'Tambah Nominal Keuangan';

        return view('jenis_biaya.create', compact('title', 'Rekening'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'kode_akun',
            'total_beban',
            'angkatan'
        ]);

        $rules = [
            'total_beban'       => 'required',
            'angkatan'          => 'required',
            'kode_akun'         => 'required'
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $Jenis_biaya = Jenis_Biaya::create([
            'angkatan'          => $request->angkatan,
            'kode_akun'         => $request->kode_akun,
            'total_beban'       => str_replace(',', '', str_replace('.00', '', $request->total_beban)),
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Jenis Biaya berhasil ditambahkan',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jenis_biaya $Jenis_biaya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jenis_biaya $Jenis_biaya)
    {
        $Rekening = Rekening::where('kode_akun', 'like', '1.1.03.%')->get();
        $Jenis_biaya->load('get_rekening');
        $title = 'Edit Nominal Keuangan';

        return view('jenis_biaya.edit', compact('title', 'Rekening','Jenis_biaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jenis_Biaya $Jenis_biaya)
    {
        $data = $request->only([
            'kode_akun',
            'total_beban',
            'angkatan'
        ]);

        $rules = [
            'total_beban'       => 'required',
            'angkatan'          => 'required',
            'kode_akun'         => 'required'
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $Jenis_biaya->update([
            'angkatan'          => $request->angkatan,
            'kode_akun'         => $request->kode_akun,
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
    public function destroy(Jenis_biaya $Jenis_biaya)
    {
        $Jenis_biaya->delete();
        return response()->json([
            'success'       => true,
            'msg'           => 'Data Biaya berhasil dihapus',
            'biaya'         => $Jenis_biaya
        ]);
    }
}
