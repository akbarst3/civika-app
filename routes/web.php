<?php

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