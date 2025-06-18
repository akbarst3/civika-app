<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PKLController;
use App\Models\Prodi;

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

Route::get('/data-kp-pkl', function () {
    return view('data-kp-pkl-view/data-kp-pkl');
})->name('data-kp-pkl');

Route::post('/data-kp-pkl/import-data', [PKLController::class, 'import'])->name('data-kp-pkl.import-data');

Route::get('/data-kp-pkl/import', [PKLController::class, 'formImport'])->name('data-kp-pkl.import-form');

Route::get('/data-kp-pkl/generate-pdpt', [PKLController::class, 'formGeneratePDPTKpPkl'])->name('data-kp-pkl.generate-pdpt-form');

Route::get('/data-kp-pkl/generate-honor', [PKLController::class, 'formGenerateHonorKpPkl'])->name('data-kp-pkl.generate-honor-form');

Route::get('/data-kp-pkl/generate-pdpt/download', [PKLController::class, 'generatePDPTKpPkl'])->name('data-kp-pkl.generate-pdpt-download');

Route::get('/data-kp-pkl/laporan-honor/download', [PKLController::class, 'generateHonorKpPkl'])->name('data-kp-pkl.laporan-honor-download');

Route::get('/data-kp-pkl/generate-honor/generate', [PKLController::class, 'generateHonorKpPkl'])->name('data-kp-pkl.generate-honor-download');

Route::get('/data-kp-pkl/laporan-honor/display', [PKLController::class, 'displayHonorKpPkl'])->name('data-kp-pkl.laporan-honor-display');
