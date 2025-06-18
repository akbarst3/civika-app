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

Route::get('/datapkl', function () {
    return view('pkl-view/datapkl');
})->name('datapkl');

Route::post('/datapkl/import', [PKLController::class, 'import'])->name('kp-pkl.import');

Route::get('/datapkl/import', function () {
    return view('pkl-view/importdatapkl', ['prodis' => Prodi::all()]);
})->name('kp-pkl.import.form');
Route::get('/generate-pdpt-pkl', [PKLController::class, 'form'])->name('generate.laporan.form');
Route::get('/laporan/download', [PKLController::class, 'handleDownload'])->name('laporan.download');


Route::get('/kp-pkl/form', [PKLController::class, 'form'])->name('kp-pkl.form');
Route::get('/kp-pkl/download', [PKLController::class, 'downloadHonorKpPkl'])->name('kp-pkl.download');
