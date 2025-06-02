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
//Contoh penggunaan middleware

// Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
//     Route::get('/mahasiswa/dashboard', function () {
//         return view('mahasiswa.dashboard');
//     })->name('mahasiswa.dashboard');
// });

// Route::middleware(['auth', 'role:dosen'])->group(function () {
//     Route::get('/dosen/dashboard', function () {
//         return view('dosen.dashboard');
//     })->name('dosen.dashboard');
// });

// Route::middleware(['auth', 'role:tata_usaha'])->group(function () {
//     Route::get('/tata-usaha/dashboard', function () {
//         return view('tata-usaha.dashboard');
//     })->name('tata_usaha.dashboard');
// });

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
