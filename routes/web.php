<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => ['auth'], 'prefix' => 'app'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
