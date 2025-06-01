<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpPkl;
use App\Models\Dosen;
use App\Models\Prodi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PKLController extends Controller
{
    public function handleDownload(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
            'jenis_laporan' => 'required|in:pdpt,honor'
        ]);

        if ($request->jenis_laporan === 'pdpt') {
            return $this->generatePDPT($request);
        }
        abort(404);
    }

    public function generatePDPT(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required'
        ]);

        $prodi = Prodi::findOrFail($request->prodi);
        $namaProdi = $prodi->nama_prodi;

        $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
        $data = KpPkl::with([
            'mahasiswa.kelas',
            'pembimbing1.dosen',
            'pembimbing2.dosen',
            'penguji1.dosen',
            'penguji2.dosen',
        ])
        ->whereHas('mahasiswa.kelas', function ($query) use ($request) {
            $query->where('kode_prodi', $request->prodi)
                ->where('angkatan', $request->angkatan);
        })
        ->get()
        ->map(function ($item) {
            return [
                'nim' => $item->mahasiswa->nim,
                'nama_mhs' => $item->mahasiswa->nama_mhs,
                'nama_perusahaan' => $item->nama_perusahaan,
                'pembimbing_1' => $item->pembimbing1->dosen->nama_dosen ?? '-',
                'nidn_pembimbing_1' => $item->pembimbing1->dosen->nidn ?? '-',
                'pembimbing_2' => $item->pembimbing2->dosen->nama_dosen ?? '-',
                'nidn_pembimbing_2' => $item->pembimbing2->dosen->nidn ?? '-',
                'penguji_1' => $item->penguji1->dosen->nama_dosen ?? '-',
                'nidn_penguji_1' => $item->penguji1->dosen->nidn ?? '-',
                'penguji_2' => $item->penguji2->dosen->nama_dosen ?? '-',
                'nidn_penguji_2' => $item->penguji2->dosen->nidn ?? '-',
            ];
        });

        $currentDate = now()->format('d-m-Y');
        $filename = "laporan_pdpt_kp_pkl_{$namaProdi}_{$request->angkatan}_{$currentDate}.pdf";

        $pdf = Pdf::loadView('pkl-view.laporan-pdpt-kp-pkl', compact('data', 'kaprodi'));
        return $pdf->download($filename);
    }

    public function form()
    {
        $angkatans = DB::table('mahasiswa')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan');

        return view('pkl-view.generate-pdpt-pkl', compact('angkatans'));
    }
}
