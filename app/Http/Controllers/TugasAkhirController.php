<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasAkhir;
use App\Models\Membimbing;
use App\Models\Menguji;

class TugasAkhirController extends Controller
{
    public function getDataTA(Request $request)
{
    $query = TugasAkhir::with([
        'mahasiswa' => function ($query) {
            $query->select('nim', 'nama_mhs', 'nama_kelas');
        },
        'membimbing.dosen' => function ($query) {
            $query->select('kode_dosen', 'nama_dosen');
        },
        'menguji.dosen' => function ($query) {
            $query->select('kode_dosen', 'nama_dosen');
        }
    ]);

    // Filter berdasarkan angkatan (misalnya, nama_kelas berisi info angkatan)
    if ($request->has('angkatan') && $request->angkatan != '') {
        $query->whereHas('mahasiswa', function ($q) use ($request) {
            $q->where('nama_kelas', 'like', '%' . $request->angkatan . '%');
        });
    }

    // Pencarian berdasarkan nama mahasiswa atau topik
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('topik', 'like', '%' . $search . '%')
              ->orWhereHas('mahasiswa', function ($q) use ($search) {
                  $q->where('nama_mhs', 'like', '%' . $search . '%');
              });
        });
    }

    $tugasAkhir = $query->get();

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

    public function showPembimbingPengujiView(Request $request)
    {
        $query = TugasAkhir::with([
            'mahasiswa' => function ($query) {
                $query->select('nim', 'nama_mhs', 'nama_kelas');
            },
            'membimbing.dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen');
            },
            'menguji.dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen');
            }
        ]);

        // Filter berdasarkan angkatan
        if ($request->filled('angkatan')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->angkatan . '%');
            });
        }

        // Pencarian berdasarkan nama mahasiswa atau topik
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('topik', 'like', '%' . $search . '%')
                ->orWhereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('nama_mhs', 'like', '%' . $search . '%');
                });
            });
        }

        $tugasAkhir = $query->get();

        return view('tugas-akhir-view.pembimbing_penguji', compact('tugasAkhir'));
    }

}