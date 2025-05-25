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
Route::get('/upload-form', [ImportBukuBesarController::class, 'showUploadForm'])->name('excel.upload.form');

// Route untuk menangani proses impor file excel
Route::post('/import-excel', [ImportBukuBesarController::class, 'importExcel'])->name('excel.import');
