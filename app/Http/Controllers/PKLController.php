<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpPkl;

class PKLController extends Controller
{
    public function getDatapkl(Request $request)
    {
        $query = KpPkl::with([
            'mahasiswa' => function ($query) {
                $query->select('nim', 'nama_mhs', 'nama_kelas');
            },
            'dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen');
            }
        ]);

        // Filter berdasarkan angkatan
        if ($request->has('angkatan') && $request->angkatan != '') {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->angkatan . '%');
            });
        }

        // Pencarian berdasarkan nama mahasiswa atau perusahaan
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', '%' . $search . '%')
                  ->orWhereHas('mahasiswa', function ($q) use ($search) {
                      $q->where('nama_mhs', 'like', '%' . $search . '%');
                  });
            });
        }

        // Pagination
        $kpPkl = $query->paginate(10);

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
                    [
                        'kode_dosen' => $pkl->kode_dosen,
                        'nama_dosen' => $pkl->dosen->nama_dosen,
                    ],
                    // Placeholder untuk pembimbing kedua (sesuaikan jika ada data)
                    [
                        'kode_dosen' => null,
                        'nama_dosen' => null,
                    ]
                ],
                'penguji' => [
                    // Placeholder untuk penguji (sesuaikan jika ada data)
                    [
                        'kode_dosen' => null,
                        'nama_dosen' => null,
                    ],
                    [
                        'kode_dosen' => null,
                        'nama_dosen' => null,
                    ]
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'links' => $kpPkl->links()->toArray(),
            'meta' => [
                'current_page' => $kpPkl->currentPage(),
                'last_page' => $kpPkl->lastPage(),
            ]
        ], 200);
    }
}