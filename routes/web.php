<?php

use App\Http\Controllers\FormSuratController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/template', function () {
    return view('surat-view.template-surat-beasiswa');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    //Dashboard Pengaju
    Route::get('/mahasiswa/dashboard-pengaju', [SuratController::class, 'viewDashboardPengaju'])->name('dashboard-pengaju');

    //Form Pengajuan
    Route::get('/mahasiswa/pengajuan-surat', [SuratController::class, 'createPengajuan'])->name('pengajuan-surat');
    Route::post('/mahasiswa/pengajuan-surat-send', [SuratController::class, 'storePengajuan'])->name('pengajuan-surat-store');

    //Daftar Surat Pengaju
    Route::get('/mahasiswa/daftar-pengajuan-surat', [SuratController::class, 'indexDaftarSurat'])->name('daftar-pengajuan-surat');
});

Route::middleware(['auth', 'role:tata_usaha,dosen'])->group(function () {
    //Dashboard Reviewer1
    Route::get('/TU/dashboard-reviewer1', [SuratController::class, 'viewDashboardReviewer1'])->name('dashboard-reviewer1');

    //Daftar Surat Pengaju
    Route::get('/TU/daftar-verifikasi-surat', [SuratController::class, 'indexDaftarSuratVerifikasi'])->name('daftar-verifikasi-surat');

    //Detail Pengajuan Surat
    Route::get('/TU/detail-pengajuan-surat/{kode_surat}', [SuratController::class, 'indexDetailPengajuanSurat'])->name('detail-pengajuan-surat');
    Route::put('TU/detail-pengajuan-surat/{kode_surat}/update', [SuratController::class, 'updateDetailPengajuanSurat'])->name('detail-pengajuan-surat-update');

    //Update Surat Jika Ada Kesalahan Minor 
    Route::put('TU/pengajuan-surat/{kode_surat}/update', [SuratController::class, 'updatePengajuan'])->name('pengajuan-surat-update');
});

Route::get('/TU/pengajuan-surat-download/{kode_surat}', [SuratController::class, 'createSurat'])->name('pengajuan-surat-create');

Route::get('/riwayat-pengajuan-surat-dosen', function () {
    return view('surat-view.dosen.riwayat-pengajuan-surat-dosen');
});

