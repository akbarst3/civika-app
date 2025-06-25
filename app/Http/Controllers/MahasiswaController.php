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

        try {
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

            return view('mahasiswa-view.data-mahasiswa', compact('mahasiswa', 'angkatanList', 'kelasList', 'prodiList'))
                ->with('success', true)
                ->with('message', 'Data retrieved successfully.');
        } catch (\Exception $e) {
            Log::error('Error fetching mahasiswa data: ' . $e->getMessage());
            return view('mahasiswa-view.data-mahasiswa', compact('mahasiswa', 'angkatanList', 'kelasList', 'prodiList'))
                ->with('success', false)
                ->with('message', 'Failed to retrieve data: ' . $e->getMessage());
        }
    }

    public function showDetail($nim)
    {
        try {
            $mahasiswa = Mahasiswa::with(['dataTinggal', 'kelas.prodi'])->where('nim', $nim)->firstOrFail();
            return view('mahasiswa-view.data-mahasiswa-detail', compact('mahasiswa'))
                ->with('success', true)
                ->with('message', 'Detail berhasil diambil.');
        } catch (\Exception $e) {
            Log::error('Error fetching mahasiswa detail: ' . $e->getMessage());
            return redirect()->route('mahasiswa.list')
                ->with('success', false)
                ->with('message', 'Gagal mengambil detail: ' . $e->getMessage());
        }
    }

    public function showImportMahasiswa()
    {
        try {
            $angkatanList = Kelas::distinct()->pluck('angkatan')->sort()->values();
            return view('mahasiswa-view.import-data-mahasiswa', compact('angkatanList'))
                ->with('success', true)
                ->with('message', 'Halaman impor berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading import page: ' . $e->getMessage());
            return view('mahasiswa-view.import-data-mahasiswa')
                ->with('success', false)
                ->with('message', 'Gagal memuat halaman impor: ' . $e->getMessage());
        }
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        try {
            Excel::import(new DataMahasiswaImport($request->angkatan), $request->file('file'));
            return redirect()->back()
                ->with('success', true)
                ->with('message', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return redirect()->back()
                ->with('success', false)
                ->with('message', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
