<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PKLController;

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


Route::get('/kp-pkl/form', [PKLController::class, 'form'])->name('kp-pkl.form');
Route::get('/kp-pkl/download', [PKLController::class, 'downloadHonorKpPkl'])->name('kp-pkl.download');
