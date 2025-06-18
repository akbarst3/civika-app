<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Imports\DataMahasiswaImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class MahasiswaController extends Controller
{
    public function showListMahasiswa(Request $request)
    {
        // Fetch filter options
        $angkatanList = Kelas::distinct()->pluck('angkatan')->sort()->values();
        $kelasList = Kelas::distinct()->pluck('nama_kelas')->sort()->values();
        $prodiList = Prodi::pluck('nama_prodi', 'kode_prodi');

        // Fetch Mahasiswa data with related DataTinggal and Kelas
        $mahasiswa = Mahasiswa::with(['dataTinggal', 'kelas.prodi'])
            ->when($request->search, function ($query, $search) {
                return $query->where('nim', 'like', "%{$search}%")
                    ->orWhere('nama_mhs', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->angkatan, function ($query, $angkatan) {
                return $query->whereHas('kelas', function ($q) use ($angkatan) {
                    $q->where('angkatan', $angkatan);
                });
            })
            ->when($request->kelas, function ($query, $kelas) {
                return $query->whereHas('kelas', function ($q) use ($kelas) {
                    $q->where('nama_kelas', $kelas);
                });
            })
            ->when($request->prodi, function ($query, $prodi) {
                return $query->whereHas('kelas.prodi', function ($q) use ($prodi) {
                    $q->where('kode_prodi', $prodi);
                });
            })
            ->paginate(10); // Paginate with 10 records per page

        return view('mahasiswa-view.data-mahasiswa', compact('mahasiswa', 'angkatanList', 'kelasList', 'prodiList'));
    }

    public function showDetail($nim)
    {
        $mahasiswa = Mahasiswa::with(['dataTinggal', 'kelas.prodi'])->where('nim', $nim)->firstOrFail();
        return view('mahasiswa-view.data-mahasiswa-detail', compact('mahasiswa'));
    }

    public function showImportMahasiswa()
    {
        $angkatanList = Kelas::distinct()->pluck('angkatan')->sort()->values();

        return view('mahasiswa-view.import-data-mahasiswa', compact('angkatanList'));
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        try {
            Excel::import(new DataMahasiswaImport($request->angkatan), $request->file('file'));
            return redirect()->back()->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to import data: ' . $e->getMessage());
        }
    }
}
