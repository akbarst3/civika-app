<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatistikController;

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

// Default route
Route::get('/', function () {
    return redirect()->route('statistik.index');
});

// Route Statistik
Route::prefix('statistik')->name('statistik.')->group(function () {
    // Halaman main statistik
    Route::get('/', [StatistikController::class, 'index'])->name('index');
});
