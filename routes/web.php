<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasAkhirController;

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

Route::get('/generate-honor-ta', [TugasAkhirController::class, 'form'])->name('generate.honor.ta.form');
Route::post('/generate-honor-ta', [TugasAkhirController::class, 'handleDownloadHonor'])->name('generate.honor.ta');
Route::get('/display-honor-ta', [TugasAkhirController::class, 'displayHonorTA'])->name('display.honor.ta');
Route::get('/ta/form', [TugasAkhirController::class, 'form'])->name('ta.form');
Route::get('/ta/download', [TugasAkhirController::class, 'handleDownload'])->name('ta.download');
Route::get('/generate-pdpt-ta', [TugasAkhirController::class, 'pdptForm'])->name('generate.pdpt.ta.form');
Route::post('/generate-pdpt-ta', [TugasAkhirController::class, 'handleDownloadPDPT'])->name('generate.pdpt.ta');


Route::post('/generate-pdpt-tu', function () {
    return redirect()->route('generate.pdpt.ta.form')->with('success', 'Laporan PDPT TA generated successfully!');
})->name('generate.pdpt.tu');

