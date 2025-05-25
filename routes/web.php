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

Route::get('/generate-honor-ta', function () {
    return view('tugas-akhir-view.generate-honor-ta');
})->name('generate.honor.ta.form');

Route::post('/generate-honor-ta', function () {
    return redirect()->route('generate.honor.ta.form')->with('success', 'Laporan Honor TA generated successfully!');
})->name('generate.honor.ta');
