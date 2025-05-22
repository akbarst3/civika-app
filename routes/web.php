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

