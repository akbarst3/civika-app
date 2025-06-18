<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpPkl;
use App\Models\Dosen;
use App\Models\Prodi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Imports\KpPklImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

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
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
            'prodi' => 'required|exists:prodi,kode_prodi',
        ]);

        try {
            Excel::import(new KpPklImport($request->angkatan, $request->prodi), $request->file('file'));
            return redirect()->back()->with('success', 'Data KP/PKL imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing KP/PKL data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to import KP/PKL data: ' . $e->getMessage());
        }
    }

    public function downloadHonorKpPkl(Request $request)
    {
        if ($request->jenis_laporan === 'honorKpPkl') {
            return $this->generateHonorKpPkl($request);
        }
        abort(404);
    }

    public function generateHonorKpPkl(Request $request)
    {
        $prodi = Prodi::find($request->prodi);
        $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
        $sekretaris = Dosen::where('jabatan_dosen', 'Sekretaris 2')->first();

        if (!$prodi) {
            abort(404, 'Program studi tidak ditemukan');
        }

        $angkatan = $request->angkatan;
        $isD3 = $prodi->nama_prodi === 'D3';
        $KpPklYear = $angkatan + ($isD3 ? 3 : 4);
        $tahunAkademik = ($KpPklYear - 1) . '/' . $KpPklYear;

        $data = Dosen::with([
            'membimbingKpPkl.kpPkl.mahasiswa.kelas',
            'mengujiKpPkl.kpPkl.mahasiswa.kelas'
        ])
        ->get()
        ->map(function ($dosen) use ($request) {
            $pembimbing1Count = $dosen->membimbingKpPkl
                ->where('pembimbing_ke', 1)
                ->filter(function ($relasi) use ($request) {
                    return $relasi->kpPkl &&
                        $relasi->kpPkl->mahasiswa &&
                        $relasi->kpPkl->mahasiswa->kelas &&
                        $relasi->kpPkl->mahasiswa->kelas->kode_prodi == $request->prodi &&
                        $relasi->kpPkl->mahasiswa->kelas->angkatan == $request->angkatan;
                })
                ->count();

            $pembimbing2Count = $dosen->membimbingKpPkl
                ->where('pembimbing_ke', 2)
                ->filter(function ($relasi) use ($request) {
                    return $relasi->kpPkl &&
                        $relasi->kpPkl->mahasiswa &&
                        $relasi->kpPkl->mahasiswa->kelas &&
                        $relasi->kpPkl->mahasiswa->kelas->kode_prodi == $request->prodi &&
                        $relasi->kpPkl->mahasiswa->kelas->angkatan == $request->angkatan;
                })
                ->count();

            $penguji1Count = $dosen->mengujiKpPkl
                ->where('penguji_ke', 1)
                ->filter(function ($relasi) use ($request) {
                    return $relasi->kpPkl &&
                        $relasi->kpPkl->mahasiswa &&
                        $relasi->kpPkl->mahasiswa->kelas &&
                        $relasi->kpPkl->mahasiswa->kelas->kode_prodi == $request->prodi &&
                        $relasi->kpPkl->mahasiswa->kelas->angkatan == $request->angkatan;
                })
                ->count();

            $penguji2Count = $dosen->mengujiKpPkl
                ->where('penguji_ke', 2)
                ->filter(function ($relasi) use ($request) {
                    return $relasi->kpPkl &&
                        $relasi->kpPkl->mahasiswa &&
                        $relasi->kpPkl->mahasiswa->kelas &&
                        $relasi->kpPkl->mahasiswa->kelas->kode_prodi == $request->prodi &&
                        $relasi->kpPkl->mahasiswa->kelas->angkatan == $request->angkatan;
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

        $prodiType = $prodi->nama_prodi === 'D3' ? 'D-III' : 'D-IV';

        $currentDate = now()->format('d-m-Y');
        $filename = "laporan_honor_kp-pkl_{$prodi->nama_prodi}_{$request->angkatan}_{$currentDate}.pdf";
        $pdf = Pdf::loadView('pkl-view.laporan-honor-kp-pkl', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris', 'prodiType'));
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

        return view('pkl-view.generate-laporan', compact('angkatans'));
    }
}
