<?php

namespace App\Http\Controllers;

use App\Models\KpPkl;
use Illuminate\Http\JsonResponse;

class PKLController extends Controller
{
    /**
     * Mengambil daftar pembimbing dan penguji Kerja Praktik.
     *
     * @return JsonResponse
     */
    public function getPembimbingPenguji(): JsonResponse
    {
        // Ambil data KP/PKL dengan relasi mahasiswa dan dosen
        $kpPkls = KpPkl::with(['mahasiswa', 'dosen'])->get();

        // Transformasi data untuk respons
        $data = $kpPkls->map(function ($kp, $index) {
            return [
                'no' => $index + 1,
                'nim' => $kp->nim,
                'nama_mhs' => $kp->mahasiswa->nama_mhs ?? '-',
                'nama_perusahaan' => $kp->nama_perusahaan,
                'pembimbing1' => $kp->dosen ? $kp->dosen->nama_dosen : '-',
                'nip_pembimbing1' => $kp->dosen ? $kp->dosen->nip : '-',
                'pembimbing2' => '-', // Placeholder: Tidak ada data pembimbing kedua
                'nip_pembimbing2' => '-', // Placeholder
                'penguji1' => '-', // Placeholder: Tidak ada data penguji
                'nip_penguji1' => '-', // Placeholder
                'penguji2' => '-', // Placeholder
                'nip_penguji2' => '-' // Placeholder
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}