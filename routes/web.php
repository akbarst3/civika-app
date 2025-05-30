<?php

use App\Http\Controllers\FormSuratController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;
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

//Contoh penggunaan middleware

// Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
//     Route::get('/mahasiswa/dashboard', function () {
//         return view('mahasiswa.dashboard');
//     })->name('mahasiswa.dashboard');
// });

// Route::middleware(['auth', 'role:dosen'])->group(function () {
//     Route::get('/dosen/dashboard', function () {
//         return view('dosen.dashboard');
//     })->name('dosen.dashboard');
// });

// Route::middleware(['auth', 'role:tata_usaha'])->group(function () {
//     Route::get('/tata-usaha/dashboard', function () {
//         return view('tata-usaha.dashboard');
//     })->name('tata_usaha.dashboard');
// });

Route::get('/', function () {
    return view('welcome');
});

//Dashboard Reviewer1
Route::get('/TU/dashboard-reviewer1', [SuratController::class, 'viewDashboardReviewer1'])->name('dashboard-reviewer1');

//Daftar Surat Pengaju
Route::get('/TU/daftar-surat-disetujui', [SuratController::class, 'indexDaftarSuratDisetujui'])->name('daftar-surat-disetujui');
Route::get('/TU/daftar-verifikasi-surat', [SuratController::class, 'indexDaftarSuratVerifikasi'])->name('daftar-verifikasi-surat');

//Detail Pengajuan Surat
Route::get('/TU/detail-pengajuan-surat/{kode_surat}', [SuratController::class, 'indexDetailPengajuanSurat'])->name('detail-pengajuan-surat');
Route::put('TU/detail-pengajuan-surat/{kode_surat}/update', [SuratController::class, 'updateDetailPengajuanSurat'])->name('detail-pengajuan-surat-update');





//Dashboard Reviewer2
Route::get('/kaprodi/dashboard-reviewer2', [SuratController::class, 'viewDashboardReviewer2'])->name('dashboard-reviewer2');

//Daftar Surat Pengaju 2
Route::get('/kaprodi/daftar-surat-disetujui2', [SuratController::class, 'indexDaftarSuratDisetujui2'])->name('daftar-surat-disetujui2');
Route::get('/kaprodi/daftar-verifikasi-surat2', [SuratController::class, 'indexDaftarSuratVerifikasi2'])->name('daftar-verifikasi-surat2');



//Dashboard Pengaju
Route::get('/mahasiswa/dashboard-pengaju', [SuratController::class, 'viewDashboardPengaju'])->name('dashboard-pengaju');

//Form Pengajuan
Route::get('/mahasiswa/pengajuan-surat', [SuratController::class, 'createPengajuan'])->name('pengajuan-surat');
Route::post('/mahasiswa/pengajuan-surat-send', [SuratController::class, 'storePengajuan'])->name('pengajuan-surat-store');

//Daftar Surat Pengaju
Route::get('/mahasiswa/daftar-pengajuan-surat', [SuratController::class, 'indexDaftarSurat'])->name('daftar-pengajuan-surat');


Route::get('/riwayat-pengajuan-surat', function () {
    try {
        return view('surat-view.TU.riwayat-pengajuan-surat');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->name('riwayat-pengajuan-surat');


Route::get('/verifikasi-surat-tu', function () {
    try {
        return view('surat-view.TU.verifikasi-surat-tu');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->name('verifikasi-surat-tu');


Route::get('/riwayat-pengajuan-surat-dosen', function () {
    try {
        return view('surat-view.dosen.riwayat-pengajuan-surat-dosen');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->name('riwayat-pengajuan-surat-dosen');


