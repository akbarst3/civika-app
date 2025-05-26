<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasAkhir;
use App\Models\Membimbing;
use App\Models\Menguji;

class TugasAkhirController extends Controller
{
    public function index()
    {
        // Mengambil semua data tugas akhir beserta relasi mahasiswa, pembimbing, dan penguji
        $tugasAkhir = TugasAkhir::with([
            'mahasiswa' => function ($query) {
                $query->select('nim', 'nama_mhs', 'nama_kelas');
            },
            'membimbing.dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen'); // Asumsi ada kolom nama_dosen di tabel dosen
            },
            'menguji.dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen');
            }
        ])->get();

        // Format data untuk response
        $data = $tugasAkhir->map(function ($ta) {
            return [
                'kota' => $ta->kota,
                'nim' => $ta->nim,
                'nama_mahasiswa' => $ta->mahasiswa->nama_mhs,
                'kelas' => $ta->mahasiswa->nama_kelas,
                'topik' => $ta->topik,
                'pembimbing' => $ta->membimbing->map(function ($membimbing) {
                    return [
                        'kode_dosen' => $membimbing->kode_dosen,
                        'nama_dosen' => $membimbing->dosen->nama_dosen,
                    ];
                })->toArray(),
                'penguji' => $ta->menguji->map(function ($menguji) {
                    return [
                        'kode_dosen' => $menguji->kode_dosen,
                        'nama_dosen' => $menguji->dosen->nama_dosen,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}