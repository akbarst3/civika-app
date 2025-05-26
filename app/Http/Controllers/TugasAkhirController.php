<?php

namespace App\Http\Controllers;

use App\Imports\DataTAImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TugasAkhirController extends Controller
{
    public function import(Request $request)
    {
        // Validating the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'angkatan' => 'required|digits:4'
        ]);

        try {
            // Importing the file using DataTAImport
            Excel::import(new DataTAImport($request->angkatan), $request->file('file'));

            return redirect()->back()->with('success', 'Data Tugas Akhir berhasil diimpor.');
        } catch (\Exception $e) {
            // Logging the error and returning a failure message
            Log::error('Error importing Tugas Akhir data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
