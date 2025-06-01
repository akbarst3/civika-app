<?php

use App\Http\Controllers\MahasiswaController;
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

Route::get('/datamahasiswa/import', [MahasiswaController::class, 'showImportMahasiswa'])->name('datamahasiswa.import');

Route::post('/datamahasiswa/import', [MahasiswaController::class, 'importMahasiswa'])->name('mahasiswa.import');
