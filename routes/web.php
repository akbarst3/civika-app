<?php

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

Route::get('/generate-pdpt-ta', [TugasAkhirController::class, 'pdptForm'])->name('generate.pdpt.ta.form');
Route::post('/generate-pdpt-ta', [TugasAkhirController::class, 'handleDownload'])->name('generate.pdpt.ta');


Route::post('/generate-pdpt-tu', function () {
    return redirect()->route('generate.pdpt.ta.form')->with('success', 'Laporan PDPT TA generated successfully!');
})->name('generate.pdpt.tu');

