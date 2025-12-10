<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KeuanganBayarController;
use App\Http\Controllers\JenisBiayaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => ['auth'], 'prefix' => 'app'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfilController::class, 'index']);

    Route::resource('/keuangan-bayar', KeuanganBayarController::class);

    Route::resource('/Jenis-biaya', JenisBiayaController::class);

    Route::get('/siswa/listTahun', [SiswaController::class, 'listTahun']);
    Route::get('/siswa/listKelas', [SiswaController::class, 'listKelas']);
    Route::get('/siswa/printSiswa', [SiswaController::class, 'printSiswa']);
    Route::resource('/siswa', SiswaController::class);

    Route::get('/laporan-keuangan', [LaporanController::class, 'index']);
    Route::get('/pelaporan/preview', [LaporanController::class, 'preview']);
    Route::get('/pelaporan/sub_laporan/{file}', [LaporanController::class, 'subLaporan']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
