<?php

use App\Http\Controllers\MahasiswaController;
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

Route::get('/generate-honor-ta', [TugasAkhirController::class, 'form'])->name('generate.honor.ta.form');
Route::post('/generate-honor-ta', [TugasAkhirController::class, 'handleDownloadHonor'])->name('generate.honor.ta');
Route::get('/display-honor-ta', [TugasAkhirController::class, 'displayHonorTA'])->name('display.honor.ta');
Route::get('/ta/form', [TugasAkhirController::class, 'formGenerateHonor'])->name('ta.form');
Route::get('/ta/download', [TugasAkhirController::class, 'handleDownload'])->name('ta.download');
Route::get('/generate-pdpt-ta', [TugasAkhirController::class, 'pdptForm'])->name('generate.pdpt.ta.form');
Route::post('/generate-pdpt-ta', [TugasAkhirController::class, 'handleDownloadPDPT'])->name('generate.pdpt.ta');


Route::post('/generate-pdpt-tu', function () {
    return redirect()->route('generate.pdpt.ta.form')->with('success', 'Laporan PDPT TA generated successfully!');
})->name('generate.pdpt.tu');


Route::get('/datamahasiswa', function () {
    return view('mahasiswa-view/datamhs');
})->name('datamahasiswa');

Route::get('/datamahasiswa/import', function () {
    return view('mahasiswa-view/importdatamhs');
})->name('datamahasiswa.import');

Route::post('/data/ta/import/DataTA' ,[TugasAkhirController::class, 'import'])->name('data-ta.importData');

Route::get('/data/ta/import', [TugasAkhirController::class, 'formImport'])->name('data-ta.import');;

Route::get('/data/ta', function () {
    return view('tugas-akhir-view/data-ta'); // Buat file ta.blade.php jika diperlukan
})->name('data.ta');

//Route::get('/data/ta/import', function () {
//    return view('tugas-akhir-view/import-excel-ta'); // Buat file ta_import.blade.php jika diperlukan
//})->name('data.ta.import');
