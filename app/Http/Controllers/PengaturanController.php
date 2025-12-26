<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel1;
use App\Models\AkunLevel2;
use App\Models\AkunLevel3;
use App\Models\Rekening;
use App\Models\Profil;
use App\Models\Kelas;
use App\Models\Tanda_tangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function sop()
    {
        $profil = Profil::first();

        $title = "Personalisasi SOP";
        return view('pengaturan.index', compact('title', 'profil',));
    }

    public function coa()
    {
        $title = "Chart Of Account (CoA)";
        $akun1 = AkunLevel1::with([
            'akun2',
            'akun2.akun3',
            'akun2.akun3.rek'
        ])->get();

        return view('pengaturan.coa')->with(compact('title', 'akun1'));
    }

    public function ttdPelaporan()
    {
        $title = "Pengaturan Tanda Tangan Pelaporan";
        $ttd = Tanda_tangan::first();

        $tanggal = false;
        if ($ttd) {
            $str = strpos($ttd->tanda_tangan, '{tanggal}');

            if ($str !== false) {
                $tanggal = true;
            }
        }

        return view('pengaturan.tanda_tangan')->with(compact('title', 'ttd', 'tanggal'));
    }

    public function lembaga(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telpon' => 'required',
            'penanggung_jawab' => 'required',
        ]);
        Profil::findOrFail($id)->update($request->all());

        return response()->json([
            'success' => true,
            'msg' => "Update Lembaga berhasil diproses!"

        ]);
    }

    public function logo(Request $request, $id)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profil = Profil::findOrFail($id);

        if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
            Storage::disk('public')->delete($profil->logo);
        }

        $path = $request->file('logo')->store('logo', 'public');

        $profil->update([
            'logo' => $path
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Logo berhasil diperbarui'
        ]);
    }
}
