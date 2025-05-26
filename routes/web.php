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

Route::post('/kp-pkl/import', [PKLController::class, 'import'])->name('kp-pkl.import');
Route::get('/kp-pkl/import', function () {
    return view('welcome', ['prodis' => Prodi::all()]);
})->name('kp-pkl.import.form');