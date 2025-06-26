<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Menguji;
use App\Models\Membimbing;
use Illuminate\Http\JsonResponse;

class TugasAkhirController extends Controller
{
    /**
     * Mengambil daftar pembimbing dan penguji tugas akhir.
     *
     * @return JsonResponse
     */
    public function getPembimbingPenguji(): JsonResponse
    {
        // Ambil data tugas akhir dengan relasi mahasiswa, membimbing, dan menguji
        $tugasAkhir = TugasAkhir::with([
            'mahasiswa',
            'membimbing.dosen',
            'menguji.dosen'
        ])->get();

        // Transformasi data untuk respons
        $data = $tugasAkhir->map(function ($tugas) {
            // Ambil pembimbing (maksimal 2)
            $pembimbing = $tugas->membimbing->take(2);
            $pembimbing1 = $pembimbing->get(0)->dosen ?? null;
            $pembimbing2 = $pembimbing->get(1)->dosen ?? null;

            // Ambil penguji (maksimal 2)
            $penguji = $tugas->menguji->take(2);
            $penguji1 = $penguji->get(0)->dosen ?? null;
            $penguji2 = $penguji->get(1)->dosen ?? null;

            return [
                'kota' => $tugas->kota,
                'nim' => $tugas->nim,
                'nama_mhs' => $tugas->mahasiswa->nama_mhs ?? '-',
                'topik' => $tugas->topik,
                'pembimbing1' => $pembimbing1 ? $pembimbing1->nama_dosen : '-',
                'nip_pembimbing1' => $pembimbing1 ? $pembimbing1->nip : '-',
                'pembimbing2' => $pembimbing2 ? $pembimbing2->nama_dosen : '-',
                'nip_pembimbing2' => $pembimbing2 ? $pembimbing2->nip : '-',
                'penguji1' => $penguji1 ? $penguji1->nama_dosen : '-',
                'nip_penguji1' => $penguji1 ? $penguji1->nip : '-',
                'penguji2' => $penguji2 ? $penguji2->nama_dosen : '-',
                'nip_penguji2' => $penguji2 ? $penguji2->nip : '-',
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}