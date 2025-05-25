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

Route::get('/generate-pdpt-pkl', function () {
    return view('pkl-view.generate-pdpt-pkl');
})->name('generate.pdpt.pkl.form');

Route::post('/generate-pdpt-pkl', function () {
    return redirect()->route('generate.pdpt.pkl.form')->with('success', 'Laporan PDPT PKL generated successfully!');
})->name('generate.pdpt.pkl');