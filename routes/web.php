<?php

use App\Http\Controllers\FormSuratController;
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

Route::get('/form/create', [FormSuratController::class, 'create']);
Route::post('/form', [FormSuratController::class, 'store'])->name('surat.store');