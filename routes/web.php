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

Route::get('/generate-honor-pkl', function () {
    return view('pkl-view.generate-honor-pkl');
})->name('generate.honor.pkl.form');

Route::post('/generate-honor-pkl', function () {
    return redirect()->route('generate.honor.pkl.form')->with('success', 'Laporan Honor PKL generated successfully!');
})->name('generate.honor.pkl');
