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

Route::get('/data-mahasiswa/import', [MahasiswaController::class, 'showImportMahasiswa'])->name('data-mahasiswa.import');

Route::post('/data-mahasiswa/import', [MahasiswaController::class, 'importMahasiswa'])->name('data-mahasiswa.import');

Route::get('/data-mahasiswa', [MahasiswaController::class, 'showListMahasiswa'])->name('data-mahasiswa.list');

Route::get('/data-mahasiswa/{nim}', [MahasiswaController::class, 'showDetail'])->name('data-mahasiswa.show');
