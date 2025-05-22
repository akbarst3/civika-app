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
    
    // AJAX endpoints buat data biar dinamik
    Route::get('/data', [StatistikController::class, 'getData'])->name('data');
    Route::get('/search', [StatistikController::class, 'search'])->name('search');
    Route::get('/trend-analysis', [StatistikController::class, 'getTrendAnalysis'])->name('trend-analysis');
    Route::get('/comparison', [StatistikController::class, 'getComparison'])->name('comparison');
    
    // Ekspor endpointsnya
    Route::post('/export', [StatistikController::class, 'export'])->name('export');
    Route::get('/export/{format}/{program}', [StatistikController::class, 'export'])->name('export.download');
});

// API Routes buat manggil AJAX calls
Route::prefix('api/statistik')->name('api.statistik.')->group(function () {
    // Get filtered data
    Route::post('/filter', [StatistikController::class, 'getData'])->name('filter');
    
    // UpdateReal-time data
    Route::get('/realtime/{program}', function ($program) {
        return response()->json([
            'success' => true,
            'data' => [
                'timestamp' => now()->toDateTimeString(),
                'program' => $program,
                'updates' => [
                    'total_mahasiswa' => rand(800, 1300),
                    'mahasiswa_aktif' => rand(600, 1000),
                    'rata_ipk' => round(rand(300, 400) / 100, 2),
                    'tingkat_kelulusan' => rand(85, 98)
                ]
            ],
            'message' => 'Real-time data updated'
        ]);
    })->name('realtime');
    
    // Operasi Batch
    Route::post('/batch-export', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'batch_id' => uniqid(),
                'status' => 'processing',
                'files' => [
                    'excel' => 'statistik_D3_excel.xlsx',
                    'pdf' => 'statistik_D3_report.pdf',
                    'csv' => 'statistik_D3_data.csv'
                ]
            ],
            'message' => 'Batch export started'
        ]);
    })->name('batch-export');
    
    // Statistika summary
    Route::get('/summary', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_programs' => 2,
                'total_students' => 2100,
                'active_students' => 1700,
                'average_gpa' => 3.34,
                'graduation_rate' => 93.5,
                'last_updated' => now()->toDateTimeString()
            ],
            'message' => 'Summary data retrieved'
        ]);
    })->name('summary');
});

// Route buat Download
Route::prefix('downloads')->name('downloads.')->group(function () {
    Route::get('/statistik/{filename}', function ($filename) {
        // Simulasi file di download
        $filePath = storage_path('app/downloads/' . $filename);
        
        if (!file_exists($filePath)) {
            // Bikin dummy file buat demo
            $content = "# Statistik Data Export\n";
            $content .= "Generated at: " . now()->toDateTimeString() . "\n";
            $content .= "Filename: " . $filename . "\n";
            
            file_put_contents($filePath, $content);
        }
        
        return response()->download($filePath);
    })->name('statistik');
});

// Route buat Admin (opsional)
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/statistik/settings', function () {
        return view('admin.statistik-settings');
    })->name('statistik.settings');
    
    Route::post('/statistik/update-data', function () {
        return response()->json([
            'success' => true,
            'message' => 'Data updated successfully'
        ]);
    })->name('statistik.update');
});

// Route buat Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'services' => [
            'database' => 'connected',
            'cache' => 'active',
            'storage' => 'available'
        ]
    ]);
})->name('health');

// Route dokumentasi
Route::get('/docs', function () {
    return view('docs.api-documentation');
})->name('documentation');