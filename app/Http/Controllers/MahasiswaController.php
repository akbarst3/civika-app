<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\DataMahasiswaImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class MahasiswaController extends Controller
{
    public function showImportMahasiswa()
    {
        // Fetch distinct angkatan values from Kelas model
        $angkatanList = Kelas::distinct()->pluck('angkatan')->sort()->values();

        // Pass the angkatan list to the view
        return view('mahasiswa-view.importdatamhs', compact('angkatanList'));
    }

    public function importMahasiswa(Request $request)
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
