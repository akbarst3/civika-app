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

Route::get('/datamahasiswa', function () {
    return view('mahasiswa-view/datamhs');
})->name('datamahasiswa');

Route::get('/datamahasiswa/import', function () {
    return view('mahasiswa-view/importdatamhs');
})->name('datamahasiswa.import');

Route::post('/importDataTA' ,[TugasAkhirController::class, 'import'])->name('data-ta.import');

Route::get('/data/ta', function () {
    return view('tugas-akhir-view/data-ta'); // Buat file ta.blade.php jika diperlukan
})->name('data.ta');

Route::get('/data/ta/import', function () {
    return view('tugas-akhir-view/import-excel-ta'); // Buat file ta_import.blade.php jika diperlukan
})->name('data.ta.import');