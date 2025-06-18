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

Route::get('dataTA/pembimbing_penguji',function () {
    return view('tugas-akhir-view.pembimbing_penguji');
});

Route::get('dataKP/pembimbing_penguji',function () {
    return view('pkl-view.pembimbing_penguji');
});
//Route::get('data/kp-pkl/generate-honor', function () {
//    return view('pkl-view.generate-honor-pkl');
//})->name('generate.honor.pkl.form');

Route::post('data/kp-pkl/generate-honor/action', function () {
    return redirect()->route('generate.honor.pkl.form')->with('success', 'Laporan Honor PKL generated successfully!');
})->name('generate.honor.kp-pkl');


Route::get('data/kp-pkl/generate-honor', [PKLController::class, 'form'])->name('generate.honor.kp-pkl.form');
Route::get('data/kp-pkl/generate-honor/generate', [PKLController::class, 'downloadHonorKpPkl'])->name('generate.honor.kp-pkl.download');
Route::get('/display-honor-kp-pkl', [PKLController::class, 'displayHonorKpPkl'])->name('display.honor.kp-pkl');
