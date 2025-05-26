<?php

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


// Route untuk menampilkan form upload
// Route::get('/upload-form', [ImportBukuBesarController::class, 'showUploadForm'])->name('excel.upload.form');

// Route untuk menangani proses impor file excel
// Route::post('/import-excel', [ImportBukuBesarController::class, 'importExcel'])->name('excel.import');
// Rute untuk Status Import Buku Besar
Route::get('/import-buku-besar', function () {
    return view('buku-besar-view.status-import');
})->name('import.buku-besar.index');

// Rute untuk Halaman Import Buku Besar
// Route::get('/import-buku-besar/form', function () {
//     return view('buku-besar-view.import-buku-besar');
// })->name('import.buku-besar.form');

Route::get('/import-buku-besar/form', [ImportBukuBesarController::class, 'showUploadForm'])
    ->name('import.buku-besar.form');
Route::post('/import-excel', [ImportBukuBesarController::class, 'importExcel'])
    ->name('excel.import');