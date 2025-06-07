<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class PKLController extends Controller
{

    public function downloadHonorKpPkl(Request $request)
    {
        if ($request->jenis_laporan === 'honorKpPkl') {
            return $this->generateHonorKpPkl($request);
        }
        abort(404, 'Jenis Laporan bukan Honor Kp Pkl');
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

    public function displayHonorKpPkl(Request $request)
    {

        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
        ]);

        $prodi = Prodi::where('kode_prodi', $request->input('prodi'))->first();
        if (!$prodi) 
        {
            return redirect()->back()->withErrors(['prodi' => 'Program Studi tidak valid.']);
        }

        $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
        $sekretaris = Dosen::where('jabatan_dosen', 'Sekretaris 2')->first();

        $angkatan = $request->input('angkatan');
        $isD3 = strpos($prodi->nama_prodi ?? '', 'D3') !== false;
        $kpPklYear = $angkatan + ($isD3 ? 3 : 4);
        $tahunAkademik = ($kpPklYear - 1) . '/' . $kpPklYear;

        $data = Dosen::with(['membimbingKpPkl.kpPkl.mahasiswa.kelas', 'mengujiKpPkl.kpPkl.mahasiswa.kelas'])
            ->get()
            ->map(function ($dosen) use ($request) {
                $pembimbing1Count = $dosen->membimbingKpPkl
                    ->filter(function ($membimbing) use ($request) {
                        return $membimbing->pembimbing_ke == 1 &&
                            $membimbing->kpPkl &&
                            $membimbing->kpPkl->mahasiswa &&
                            $membimbing->kpPkl->mahasiswa->kelas &&
                            $membimbing->kpPkl->mahasiswa->kelas->kode_prodi == $request->input('prodi') &&
                            $membimbing->kpPkl->mahasiswa->kelas->angkatan == $request->input('angkatan');
                    })
                    ->count();

                $pembimbing2Count = $dosen->membimbingKpPkl
                    ->filter(function ($membimbing) use ($request) {
                        return $membimbing->pembimbing_ke == 2 &&
                            $membimbing->kpPkl &&
                            $membimbing->kpPkl->mahasiswa &&
                            $membimbing->kpPkl->mahasiswa->kelas &&
                            $membimbing->kpPkl->mahasiswa->kelas->kode_prodi == $request->input('prodi') &&
                            $membimbing->kpPkl->mahasiswa->kelas->angkatan == $request->input('angkatan');
                    })
                    ->count();

                $penguji1Count = $dosen->mengujiKpPkl
                    ->filter(function ($menguji) use ($request) {
                        return $menguji->penguji_ke == 1 &&
                            $menguji->kpPkl &&
                            $menguji->kpPkl->mahasiswa &&
                            $menguji->kpPkl->mahasiswa->kelas &&
                            $menguji->kpPkl->mahasiswa->kelas->kode_prodi == $request->input('prodi') &&
                            $menguji->kpPkl->mahasiswa->kelas->angkatan == $request->input('angkatan');
                    })
                    ->count();

                $penguji2Count = $dosen->mengujiKpPkl
                    ->filter(function ($menguji) use ($request) {
                        return $menguji->penguji_ke == 2 &&
                            $menguji->kpPkl &&
                            $menguji->kpPkl->mahasiswa &&
                            $menguji->kpPkl->mahasiswa->kelas &&
                            $menguji->kpPkl->mahasiswa->kelas->kode_prodi == $request->input('prodi') &&
                            $menguji->kpPkl->mahasiswa->kelas->angkatan == $request->input('angkatan');
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

        return view('pkl-view.display-honor-pkl', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris'));
    }

    // untuk dropdown di tampilan
    public function form()
    {
        $angkatans = DB::table('kelas')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan');

        return view('pkl-view.generate-honor-pkl', compact('angkatans'));
    }
}
