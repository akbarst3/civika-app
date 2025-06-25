<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TugasAkhirController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PKLController;
use App\Models\Prodi;

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
})->name('welcome');


// Route Data Mahasiswa
Route::get('/data-mahasiswa/import', [MahasiswaController::class, 'showImportMahasiswa'])->name('data-mahasiswa.import');
Route::post('/data-mahasiswa/import', [MahasiswaController::class, 'importMahasiswa'])->name('data-mahasiswa.import');
Route::get('/data-mahasiswa', [MahasiswaController::class, 'showListMahasiswa'])->name('data-mahasiswa.list');
Route::get('/data-mahasiswa/{nim}', [MahasiswaController::class, 'showDetail'])->name('data-mahasiswa.show');
Route::get('/data-kp-pkl', function () {
    return view('data-kp-pkl-view/data-kp-pkl');
})->name('data-kp-pkl');

// Route Data PKL
Route::post('/data-kp-pkl/import-data', [PKLController::class, 'import'])->name('data-kp-pkl.import-data');
Route::get('/data-kp-pkl/import', [PKLController::class, 'formImport'])->name('data-kp-pkl.import-form');
Route::get('/data-kp-pkl/generate-pdpt', [PKLController::class, 'formGeneratePDPTKpPkl'])->name('data-kp-pkl.generate-pdpt-form');
Route::get('/data-kp-pkl/generate-honor', [PKLController::class, 'formGenerateHonorKpPkl'])->name('data-kp-pkl.generate-honor-form');
Route::get('/data-kp-pkl/generate-pdpt/download', [PKLController::class, 'generatePDPTKpPkl'])->name('data-kp-pkl.generate-pdpt-download');

// Generate Honor Tugas Akhir
Route::get('data-ta/generate-honor-ta', [TugasAkhirController::class, 'formGenerateHonor'])->name('data-ta.honor.form');
Route::post('data-ta/generate-honor-ta', [TugasAkhirController::class, 'handleDownload'])->name('data-ta.generate.honor');
Route::get('data-ta/display-honor-ta', [TugasAkhirController::class, 'displayHonorTA'])->name('data-ta.display.honor.ta');
Route::get('data-ta/ta/form', [TugasAkhirController::class, 'form'])->name('ta.form');
Route::get('data-ta/ta/download', [TugasAkhirController::class, 'handleDownload'])->name('data-ta.honor.download');

// Generate PDPT Tugas Akhir
Route::get('data-ta/generate-pdpt-ta', [TugasAkhirController::class, 'pdptForm'])->name('data-ta.generate.pdpt.form');
Route::post('data-ta/generate-pdpt-ta', [TugasAkhirController::class, 'handleDownload'])->name('data-ta.generate.pdpt');

// Import Tugas Akhir
Route::post('data-ta/import-data-ta', [TugasAkhirController::class, 'import'])->name('data-ta.import.form');
Route::get('data-ta/import-data-ta', [TugasAkhirController::class, 'formImport'])->name('data-ta.import');

// Default Data TA
Route::get('data-ta', function () {
    return view('tugas-akhir-view/data-ta');
})->name('data.ta');

Route::get('/data-kp-pkl/laporan-honor/download', [PKLController::class, 'generateHonorKpPkl'])->name('data-kp-pkl.laporan-honor-download');
Route::get('/datamahasiswa', function () {
    return view('mahasiswa-view/datamhs');
})->name('datamahasiswa');

Route::get('/data-kp-pkl/generate-honor/generate', [PKLController::class, 'generateHonorKpPkl'])->name('data-kp-pkl.generate-honor-download');

Route::get('/data-kp-pkl/laporan-honor/display', [PKLController::class, 'displayHonorKpPkl'])->name('data-kp-pkl.laporan-honor-display');

Route::get('/datamahasiswa/import', function () {
    return view('mahasiswa-view/importdatamhs');
})->name('datamahasiswa.import');
