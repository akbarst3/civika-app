<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prodi;
use App\Models\Dosen;

class TugasAkhirController extends Controller
{
    public function handleDownload(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
            'jenis_laporan' => 'required|in:honor,pdpt'
        ]);

        if ($request->jenis_laporan === 'honor') {
            return $this->generateHonorTA($request);
        }

        abort(404);
    }

    public function generateHonorTA(Request $request)
    {
        $prodi = Prodi::find($request->prodi);
        $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
        $sekretaris = Dosen::where('jabatan_dosen', 'Sekretaris 2')->first();

        $angkatan = $request->angkatan;
        $isD3 = strpos($prodi->nama_prodi ?? '', 'D3') !== false;
        $taYear = $angkatan + ($isD3 ? 3 : 4);
        $tahunAkademik = ($taYear - 1) . '/' . $taYear;

        $data = Dosen::with(['membimbing.tugasAkhir.mahasiswa.kelas', 'menguji.tugasAkhir.mahasiswa.kelas'])
            ->get()
            ->map(function ($dosen) use ($request) {
                $pembimbing1Count = $dosen->membimbing()
                    ->where('pembimbing_ke', 1)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->prodi)
                            ->where('angkatan', $request->angkatan);
                    })
                    ->count();

                $pembimbing2Count = $dosen->membimbing()
                    ->where('pembimbing_ke', 2)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->prodi)
                            ->where('angkatan', $request->angkatan);
                    })
                    ->count();

                $penguji1Count = $dosen->menguji()
                    ->where('penguji_ke', 1)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->prodi)
                            ->where('angkatan', $request->angkatan);
                    })
                    ->count();

                $penguji2Count = $dosen->menguji()
                    ->where('penguji_ke', 2)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->prodi)
                            ->where('angkatan', $request->angkatan);
                    })
                    ->count();

                return (object) [
                    'nip' => $dosen->nip,
                    'nama_dosen' => $dosen->nama_dosen,
                    'pembimbing_1_count' => $pembimbing1Count,
                    'pembimbing_2_count' => $pembimbing2Count,
                    'penguji_1_count' => $penguji1Count,
                    'penguji_2_count' => $penguji2Count,
                ];
            })
            ->filter(function ($dosen) {
                return $dosen->pembimbing_1_count > 0 || $dosen->pembimbing_2_count > 0 || 
                    $dosen->penguji_1_count > 0 || $dosen->penguji_2_count > 0;
            })
            ->values();

        $currentDate = now()->format('d-m-Y');
        $filename = "laporan_honor_ta_{$prodi->nama_prodi}_{$request->angkatan}_{$currentDate}.pdf";

        $pdf = Pdf::loadView('tugas-akhir-view.laporan-honor-ta', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris'));
        return $pdf->download($filename);
    }

    // untuk dropdown di tampilan
    public function form()
    {
        $angkatans = DB::table('mahasiswa')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan');

        return view('tugas-akhir-view.generate-laporan', compact('angkatans'));
    }
}
