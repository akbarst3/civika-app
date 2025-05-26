<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpPkl;

class PKLController extends Controller
{
    public function index()
    {
        // Mengambil semua data PKL beserta relasi mahasiswa dan dosen
        $kpPkl = KpPkl::with([
            'mahasiswa' => function ($query) {
                $query->select('nim', 'nama_mhs', 'nama_kelas');
            },
            'dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen'); // Asumsi ada kolom nama_dosen di tabel dosen
            }
        ])->get();

        // Format data untuk response
        $data = $kpPkl->map(function ($pkl) {
            return [
                'id_perusahaan' => $pkl->id_perusahaan,
                'tahun' => $pkl->tahun,
                'nim' => $pkl->nim,
                'nama_mahasiswa' => $pkl->mahasiswa->nama_mhs,
                'kelas' => $pkl->mahasiswa->nama_kelas,
                'nama_perusahaan' => $pkl->nama_perusahaan,
                'pembimbing' => [
                    'kode_dosen' => $pkl->kode_dosen,
                    'nama_dosen' => $pkl->dosen->nama_dosen,
                ],
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}