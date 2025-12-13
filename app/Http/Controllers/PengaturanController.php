<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel1;
use App\Models\AkunLevel2;
use App\Models\AkunLevel3;
use App\Models\Rekening;
use App\Models\Tanda_tangan;

use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('pengaturan.index');
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
}
