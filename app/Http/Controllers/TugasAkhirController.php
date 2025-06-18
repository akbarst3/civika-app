<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Imports\DataTAImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TugasAkhirController extends Controller
{
    public function pdptForm()
    {
        $angkatans = Kelas::select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan', 'angkatan');
        $prodis = Prodi::pluck('nama_prodi', 'kode_prodi');

        return view('tugas-akhir-view.generate-pdpt-ta', compact('angkatans', 'prodis'));
    }

    public function handleDownloadPDPT(Request $request)
    {
        $request->validate([
            'program_studi' => 'required|exists:prodi,kode_prodi',
            'angkatan' => 'required|digits:4|integer',
            'jenis_laporan' => 'required|in:pdpt',
        ]);

        if ($request->jenis_laporan === 'pdpt') {
            return $this->generatePDPT($request);
        }

        abort(404);
    }

    public function generatePDPT(Request $request)
    {
        $request->validate([
            'program_studi' => 'required|exists:prodi,kode_prodi',
            'angkatan' => 'required|string',
        ]);

        try {
            $prodi = Prodi::where('kode_prodi', $request->program_studi)->firstOrFail();
            $namaProdi = $prodi->nama_prodi;

            $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();

            $data = TugasAkhir::with([
                'mahasiswa.kelas',
                'membimbing.dosen',
                'menguji.dosen',
            ])
                ->whereHas('mahasiswa.kelas', function ($query) use ($request, $prodi) {
                    $query->where('kode_prodi', $prodi->kode_prodi)
                        ->where('angkatan', $request->angkatan);
                })
                ->get()
                ->map(function ($item) {
                    $pembimbing1 = $item->membimbing->where('pembimbing_ke', 1)->first();
                    $pembimbing2 = $item->membimbing->where('pembimbing_ke', 2)->first();
                    $penguji1 = $item->menguji->where('penguji_ke', 1)->first();
                    $penguji2 = $item->menguji->where('penguji_ke', 2)->first();

                    return [
                        'nim' => $item->mahasiswa->first()->nim ?? '-',
                        'nama_mhs' => $item->mahasiswa->first()->nama_mhs ?? '-',
                        'kota' => $item->kota,
                        'pembimbing_1' => $pembimbing1 ? $pembimbing1->dosen->nama_dosen : '-',
                        'nidn_pembimbing_1' => $pembimbing1 ? $pembimbing1->dosen->nidn : '-',
                        'pembimbing_2' => $pembimbing2 ? $pembimbing2->dosen->nama_dosen : '-',
                        'nidn_pembimbing_2' => $pembimbing2 ? $pembimbing2->dosen->nidn : '-',
                        'penguji_1' => $penguji1 ? $penguji1->dosen->nama_dosen : '-',
                        'nidn_penguji_1' => $penguji1 ? $penguji1->dosen->nidn : '-',
                        'penguji_2' => $penguji2 ? $penguji2->dosen->nama_dosen : '-',
                        'nidn_penguji_2' => $penguji2 ? $penguji2->dosen->nidn : '-',
                    ];
                });

            if ($data->isEmpty()) {
                return redirect()->route('data-ta.honor.form')->with('error', 'Tidak ada data untuk program studi dan angkatan yang dipilih.');
            }

            $currentDate = now()->format('d-m-Y');
            $filename = "laporan_pdpt_tugas_akhir_{$namaProdi}_{$request->angkatan}_{$currentDate}.pdf";

            $pdf = Pdf::loadView('tugas-akhir-view.laporan-pdpt-ta', compact('data', 'kaprodi', 'prodi'));

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->route('data-ta.honor.form')->with('error', 'Gagal menghasilkan laporan: ' . $e->getMessage());
        }
    }
    public function handleDownloadHonor(Request $request)
    {
        $request->validate([
            'prodi' => 'required|exists:prodi,kode_prodi',
            'angkatan' => 'required|digits:4|integer',
            'jenis_laporan' => 'required|in:honor,pdpt',
        ]);

        if ($request->jenis_laporan === 'honor') {
            return $this->generateHonorTA($request);
        }

        abort(404);
    }

    public function generateHonorTA(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi);
        $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
        $sekretaris = Dosen::where('jabatan_dosen', 'Sekretaris 2')->first();

        $angkatan = $request->angkatan;
        $isD3 = strpos($prodi->nama_prodi ?? '', 'D3') !== false;
        $taYear = $angkatan + ($isD3 ? 3 : 4);
        $tahunAkademik = ($taYear - 1) . '/' . $taYear;

        $data = Dosen::with(['membimbing.tugasAkhir.mahasiswa.kelas', 'menguji.tugasAkhir.mahasiswa.kelas'])
            ->whereHas('membimbing.tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                $query->where('kode_prodi', $request->prodi)
                    ->where('angkatan', $request->angkatan);
            }, '>=', 1)
            ->orWhereHas('menguji.tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                $query->where('kode_prodi', $request->prodi)
                    ->where('angkatan', $request->angkatan);
            }, '>=', 1)
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

    public function displayHonorTA(Request $request)
    {
        logger('Display Honor TA Request:', $request->all());

        $request->validate([
            'prodi' => 'required|exists:prodi,kode_prodi',
            'angkatan' => 'required|digits:4|integer',
        ], [
            'prodi.required' => 'Program Studi harus dipilih.',
            'angkatan.required' => 'Angkatan harus dipilih.',
        ]);

        $prodi = Prodi::where('kode_prodi', $request->input('prodi'))->firstOrFail();
        $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
        $sekretaris = Dosen::where('jabatan_dosen', 'Sekretaris 2')->first();

        $angkatan = $request->input('angkatan');
        $isD3 = strpos($prodi->nama_prodi ?? '', 'D3') !== false;
        $taYear = $angkatan + ($isD3 ? 3 : 4);
        $tahunAkademik = ($taYear - 1) . '/' . $taYear;

        $data = Dosen::with(['membimbing.tugasAkhir.mahasiswa.kelas', 'menguji.tugasAkhir.mahasiswa.kelas'])
            ->whereHas('membimbing.tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                $query->where('kode_prodi', $request->input('prodi'))
                    ->where('angkatan', $request->input('angkatan'));
            }, '>=', 1)
            ->orWhereHas('menguji.tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                $query->where('kode_prodi', $request->input('prodi'))
                    ->where('angkatan', $request->input('angkatan'));
            }, '>=', 1)
            ->get()
            ->map(function ($dosen) use ($request) {
                $pembimbing1Count = $dosen->membimbing()
                    ->where('pembimbing_ke', 1)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->input('prodi'))
                            ->where('angkatan', $request->input('angkatan'));
                    })
                    ->count();

                $pembimbing2Count = $dosen->membimbing()
                    ->where('pembimbing_ke', 2)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->input('prodi'))
                            ->where('angkatan', $request->input('angkatan'));
                    })
                    ->count();

                $penguji1Count = $dosen->menguji()
                    ->where('penguji_ke', 1)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->input('prodi'))
                            ->where('angkatan', $request->input('angkatan'));
                    })
                    ->count();

                $penguji2Count = $dosen->menguji()
                    ->where('penguji_ke', 2)
                    ->whereHas('tugasAkhir.mahasiswa.kelas', function ($query) use ($request) {
                        $query->where('kode_prodi', $request->input('prodi'))
                            ->where('angkatan', $request->input('angkatan'));
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

        return view('tugas-akhir-view.display-honor-ta', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris'));
    }

    public function formGenerateHonor()
    {
        $angkatans = Kelas::select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan');

        $prodis = Prodi::all();

        return view('tugas-akhir-view.generate-honor-ta', compact('angkatans', 'prodis'));
    }

    public function import(Request $request)
    {
        // Validating the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'angkatan' => 'required|digits:4',
            'prodi' => 'required|exists:prodi,kode_prodi',
        ]);

        try {
            // Importing the file with specific sheet "pembimbing_pdpt"
            Excel::import(new DataTAImport($request->angkatan, $request->prodi), $request->file('file')->getRealPath(), null, \Maatwebsite\Excel\Excel::XLSX, ['sheet' => 'pembimbing_pdpt']);

            return redirect()->back()->with('success', 'Data Tugas Akhir berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error importing Tugas Akhir data: ' . $e->getMessage(), ['exception' => $e->getTraceAsString(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function formImport()
    {
        $angkatans = DB::table('kelas')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan');

        return view('tugas-akhir-view.import-excel-ta', compact('angkatans'));
    }
}
