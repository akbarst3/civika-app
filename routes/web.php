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

// Rute untuk Status Import Buku Besar
Route::get('/import-buku-besar', function () {
    return view('buku-besar-view.status-import');
})->name('import.buku-besar.index');

// Rute untuk Halaman Import Buku Besar
Route::get('/import-buku-besar/form', function () {
    return view('buku-besar-view.import-buku-besar');
})->name('import.buku-besar.form');
