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

Route::get('/data-mahasiswa',function () {
    return view('mahasiswa-view.data-mahasiswa');
});

Route::get('/data-mahasiswa/123',function () {
    return view('mahasiswa-view.detail-data-mahasiswa');
});