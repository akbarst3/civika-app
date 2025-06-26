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
use App\Models\Mahasiswa;
use App\Models\Membimbing;
use App\Models\Memnguji;



class PKLController extends Controller
{
//    public function handleDownloadPDPTKpPkl(Request $request)
//    {
//        $request->validate([
//            'prodi' => 'required',
//            'angkatan' => 'required'
//        ]);
//
//        if ($request->jenis_laporan === 'pdpt') {
//            return $this->generatePDPT($request);
//        } else if ($request->jenis_laporan === 'honor') {
//            return $this->generateHonorKpPkl($request);
//        }
//        abort(404);
//    }
//
//    public function handleDownloadHonorKpPkl(Request $request)
//    {
//        $request->validate([
//            'prodi' => 'required',
//            'angkatan' => 'required'
//        ]);
//
//        if ($request->jenis_laporan === 'pdpt') {
//            return $this->generatePDPT($request);
//        } else if ($request->jenis_laporan === 'honor') {
//            return $this->generateHonorKpPkl($request);
//        }
//        abort(404);
//    }

    public function generatePDPTKpPkl(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required'
        ]);

        try {
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

            $pdf = Pdf::loadView('data-kp-pkl-view.laporan-pdpt-kp-pkl', compact('data', 'kaprodi'));
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error generating PDPT KP/PKL report: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function formGeneratePDPTKpPkl()
    {
        try {
            $angkatans = DB::table('kelas')
                ->select('angkatan')
                ->distinct()
                ->orderBy('angkatan', 'asc')
                ->pluck('angkatan');

            return view('data-kp-pkl-view.generate-pdpt', compact('angkatans'))
                ->with('success', 'Formulir PDPT KP/PKL berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading PDPT KP/PKL form: ' . $e->getMessage());
            return view('data-kp-pkl-view.generate-pdpt')
                ->with('error', 'Gagal memuat formulir PDPT KP/PKL: ' . $e->getMessage());
        }
    }

    public function formGenerateHonorKpPkl()
    {
        try {
            $angkatans = DB::table('kelas')
                ->select('angkatan')
                ->distinct()
                ->orderBy('angkatan', 'asc')
                ->pluck('angkatan');

            return view('data-kp-pkl-view.generate-honor', compact('angkatans'))
                ->with('success', 'Formulir Honor KP/PKL berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading Honor KP/PKL form: ' . $e->getMessage());
            return view('data-kp-pkl-view.generate-honor')
                ->with('error', 'Gagal memuat formulir Honor KP/PKL: ' . $e->getMessage());
        }
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
            return redirect()->back()
                ->with('success', 'Data KP/PKL berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error importing KP/PKL data: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengimpor data KP/PKL: ' . $e->getMessage());
        }
    }

    public function formImport()
    {
        try {
            $angkatans = DB::table('kelas')
                ->select('angkatan')
                ->distinct()
                ->orderBy('angkatan', 'asc')
                ->pluck('angkatan');

            return view('data-kp-pkl-view.import-data', compact('angkatans'))
                ->with('success', 'Formulir impor berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading import form: ' . $e->getMessage());
            return view('data-kp-pkl-view.import-data')
                ->with('error', 'Gagal memuat formulir impor: ' . $e->getMessage());
        }
    }

    public function generateHonorKpPkl(Request $request)
    {
        try {
            $prodi = Prodi::find($request->prodi);
            $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();
            $sekretaris = Dosen::where('jabatan_dosen', 'Sekretaris 2')->first();

            if (!$prodi) {
                return redirect()->back()
                    ->with('success', 'Program studi tidak ditemukan');
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
            $pdf = Pdf::loadView('data-kp-pkl-view.laporan-honor-kp-pkl', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris', 'prodiType'));
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error generating Honor KP/PKL report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat laporan Honor KP/PKL: ' . $e->getMessage());
        }
    }

    public function displayHonorKpPkl(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
        ]);

        try {
            $prodi = Prodi::where('kode_prodi', $request->input('prodi'))->first();
            if (!$prodi) {
                return redirect()->back()
                    ->with('error', 'Program Studi tidak valid.');
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

            return view('data-kp-pkl-view.display-honor', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris'))
                ->with('success', 'Data Honor KP/PKL berhasil ditampilkan.');
        } catch (\Exception $e) {
            Log::error('Error displaying Honor KP/PKL data: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menampilkan data Honor KP/PKL: ' . $e->getMessage());
        }
    }


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
        $data = [];
        foreach ($kpPkl as $pkl) {
            $data[] = [
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
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'meta' => [
                'current_page' => $kpPkl->currentPage(),
                'last_page' => $kpPkl->lastPage(),
                'total' => $kpPkl->total(),
                'per_page' => $kpPkl->perPage(),
            ]
        ], 200);
    }

    public function showPembimbingPengujiView(Request $request)
    {
        $query = KpPkl::with([
            'mahasiswa' => function ($query) {
                $query->select('nim', 'nama_mhs');
            },
            'dosen' => function ($query) {
                $query->select('kode_dosen', 'nama_dosen');
            }
        ]);

        if ($request->filled('angkatan')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', '%' . $search . '%')
                ->orWhereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('nama_mhs', 'like', '%' . $search . '%');
                });
            });
        }

        $kpPkl = $query->paginate(10);

        return view('data-kp-pkl-view.pembimbing_penguji', compact('kpPkl'));
    }
}