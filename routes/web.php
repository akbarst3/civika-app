<?php

use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportBukuBesarController; // Pastikan namespace controller benar

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
// Route::get('/buku-besar', function () {
//     return view('buku-besar-view.tabel-buku-besar');
// });
Route::get('/buku-besar', [BukuBesarController::class, 'bukuBesar'])->name('buku-besar');
Route::get('/buku-besar-dosen', [BukuBesarController::class, 'bukuBesarDosen'])->name('buku-besar-dosen');
Route::get('/buku-besar-wali-mahasiswa', [BukuBesarController::class, 'bukuBesarWaliMahasiswa'])->name('buku-besar-wali-mahasiswa');


// Route untuk menampilkan form upload
// Route::get('/upload-form', [ImportBukuBesarController::class, 'showUploadForm'])->name('excel.upload.form');

// Route untuk menangani proses impor file excel
// Route::post('/import-excel', [ImportBukuBesarController::class, 'importExcel'])->name('excel.import');
// Rute untuk Status Import Buku Besar
// Route::get('/import-buku-besar', function () {
//     return view('buku-besar-view.status-import');
// })->name('import.buku-besar.index');

// Rute untuk Halaman Import Buku Besar
// Route::get('/import-buku-besar/form', function () {
//     return view('buku-besar-view.import-buku-besar');
// })->name('import.buku-besar.form');

Route::get('/import-buku-besar/form', [BukuBesarController::class, 'showUploadForm'])
    ->name('import.buku-besar.form');
Route::get('/import-buku-besar', [BukuBesarController::class, 'cekStatus'])
    ->name('import.buku-besar.status');
Route::post('/import-excel', [BukuBesarController::class, 'importExcel'])
    ->name('excel.import');

// export pdf
Route::get('/generate-pdf', [BukuBesarController::class, 'generatePdf'])->name('generate.pdf');
// Route::post('/import-buku-besar/generate-laporan', [BukuBesarController::class, 'generateLaporan'])->name('buku.besar.generateLaporan'); //->middleware('auth'); // Pastikan route ini dilindungi
Route::post('/laporan/generate-mahasiswa', [BukuBesarController::class, 'generateLaporan'])->name('laporan.mahasiswa.generate');
