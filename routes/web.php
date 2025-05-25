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
    return view('DashboardReviewer1');
});

Route::get('/form/create', [FormSuratController::class, 'create']);
Route::post('/form', [FormSuratController::class, 'store'])->name('surat.store');

Route::get('/form/create', [FormSuratController::class, 'create']);
Route::post('/form', [FormSuratController::class, 'store'])->name('surat.store');

Route::get('/mahasiswa/pengajuan-surat', [SuratController::class, 'index'])->name('pengajuan.surat');

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


Route::get('/riwayat-pengajuan-surat-mhs', function () {
    try {
        return view('surat-view.mahasiswa.riwayat-pengajuan-surat-mhs');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->name('riwayat-pengajuan-surat-mhs');

Route::get('/riwayat-pengajuan-surat-dosen', function () {
    try {
        return view('surat-view.dosen.riwayat-pengajuan-surat-dosen');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->name('riwayat-pengajuan-surat-dosen');


