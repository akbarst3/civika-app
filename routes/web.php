<?php

use App\Http\Controllers\FormSuratController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Dashboard route
Route::get('/', [SuratController::class, 'dashboardSurat'])->name('dashboard_surat');

// Form creation and storage routes


// Pengajuan routes
Route::get('/mahasiswa/pengajuan-rokumendasi', [SuratController::class, 'dashboardSuratRokumendasi'])->name('pengajuan.rokumendasi');
Route::get('/mahasiswa/pengajuan-beasiswa', [SuratController::class, 'dashboardSuratBeasiswa'])->name('beasiswa');