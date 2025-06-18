<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TugasAkhirController;
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

Route::get('/', function () {
    return view('welcome');
});

// Generate Honor Tugas Akhir
Route::get('data-ta/generate-honor-ta', [TugasAkhirController::class, 'formGenerateHonor'])->name('data-ta.honor.form');
Route::post('data-ta/generate-honor-ta', [TugasAkhirController::class, 'handleDownloadHonor'])->name('data-ta.generate.honor');
Route::get('data-ta/display-honor-ta', [TugasAkhirController::class, 'displayHonorTA'])->name('data-ta.display.honor.ta');
Route::get('data-ta/ta/form', [TugasAkhirController::class, 'form'])->name('ta.form');
Route::get('data-ta/ta/download', [TugasAkhirController::class, 'handleDownload'])->name('data-ta.honor.download');

// Generate PDPT Tugas Akhir
Route::get('data-ta/generate-pdpt-ta', [TugasAkhirController::class, 'pdptForm'])->name('data-ta.generate.pdpt.form');
Route::post('data-ta/generate-pdpt-ta', [TugasAkhirController::class, 'handleDownloadPDPT'])->name('data-ta.generate.pdpt');
Route::post('data-ta/generate-pdpt-success', function () {
    return redirect()->route('data-ta.generate.pdpt.form')->with('success', 'Laporan PDPT TA generated successfully!');
})->name('data-ta.generate.pdpt.success');

// Import Tugas Akhir
Route::post('data-ta/import-data-ta', [TugasAkhirController::class, 'import'])->name('data-ta.import.form');
Route::get('data-ta/import-data-ta', [TugasAkhirController::class, 'formImport'])->name('data-ta.import');

// Default Data TA
Route::get('data-ta', function () {
    return view('tugas-akhir-view/data-ta');
})->name('data.ta');

Route::get('/datamahasiswa', function () {
    return view('mahasiswa-view/datamhs');
})->name('datamahasiswa');

Route::get('/datamahasiswa/import', function () {
    return view('mahasiswa-view/importdatamhs');
})->name('datamahasiswa.import');
