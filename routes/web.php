<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\JenisBiayaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanSppController;
use App\Http\Controllers\SppController;


Route::get('/', [AuthController::class, 'index'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => ['auth'], 'prefix' => 'app'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfilController::class, 'index']);

    Route::get('/Transaksi/pembayaran-spp', [TransaksiController::class, 'pembayaranSPP']);
    Route::get('/transaksi/daftar-inventaris', [TransaksiController::class, 'daftarInventaris']);
    Route::post('/transaksi/ProsesPembayaran', [TransaksiController::class, 'pembayaranSPPStore']);
    Route::get('/transaksi/kwitansi-spp', [TransaksiController::class, 'pembayaranSPPPrint']);
    Route::resource('/Transaksi', TransaksiController::class);

    Route::resource('/Jenis-biaya', JenisBiayaController::class);
    Route::get('/spp/CariSiswa', [SppController::class, 'CariSiswaAktif']);
    Route::get('/spp/Pembayaran-spp/{id}', [SppController::class, 'spp']);
    Route::resource('/spp', SppController::class);

    Route::get('/pengaturan/coa', [PengaturanController::class, 'coa']);
    Route::get('/pengaturan/ttd-pelaporan', [PengaturanController::class, 'ttdPelaporan']);
    Route::resource('/pengaturan', PengaturanController::class);

    Route::get('/siswa/listTahun', [SiswaController::class, 'listTahun']);
    Route::get('/siswa/listKelas', [SiswaController::class, 'listKelas']);
    Route::get('/siswa/printSiswa', [SiswaController::class, 'printSiswa']);
    Route::post('/siswa/mutasi', [SiswaController::class, 'mutasi']);
    Route::resource('/siswa', SiswaController::class);

    Route::get('/laporan-keuangan', [LaporanController::class, 'index']);
    Route::get('/laporan', [LaporanSppController::class, 'index']);

    Route::get('/pelaporan/preview', [LaporanController::class, 'preview']);
    Route::get('/laporan/preview', [LaporanSppController::class, 'preview']);

    Route::get('/pelaporan/sub_laporan/{file}', [LaporanController::class, 'subLaporan']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
