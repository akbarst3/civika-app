<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DataMahasiswaImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class MahasiswaController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        try {
            Excel::import(new DataMahasiswaImport($request->angkatan), $request->file('file'));
            return redirect()->back()->with('success', 'Data mahasiswa berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengimpor data mahasiswa: ' . $e->getMessage());
        }
    }
}
