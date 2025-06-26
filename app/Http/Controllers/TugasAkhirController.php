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


    public function pdptForm()
    {
        try {
            $angkatans = Kelas::select('angkatan')
                ->distinct()
                ->orderBy('angkatan', 'asc')
                ->pluck('angkatan', 'angkatan');
            $prodis = Prodi::pluck('nama_prodi', 'kode_prodi');

            return view('tugas-akhir-view.generate-pdpt-ta', compact('angkatans', 'prodis'))
                ->with('success', 'Formulir PDPT berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading PDPT form: ' . $e->getMessage());
            return view('tugas-akhir-view.generate-pdpt-ta')
                ->with('error', 'Gagal memuat formulir PDPT: ' . $e->getMessage());
        }
    }

    public function handleDownload(Request $request)
    {
        try {
            $request->validate([
                'program_studi' => 'required|exists:prodi,kode_prodi',
                'angkatan' => 'required|digits:4|integer',
                'jenis_laporan' => 'required|in:pdpt,honor',
            ]);

        if ($request->jenis_laporan === 'pdpt') {
            return $this->generatePDPT($request);
        } elseif ($request->jenis_laporan === 'honor') {
            return $this->generateHonorTA($request);
        }

            return redirect()->back()
                ->with('error', 'Jenis laporan tidak valid.');
        } catch (\Exception $e) {
            Log::error('Error handling PDPT download: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menangani pengunduhan Laporan: ' . $e->getMessage());
        }
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

            // Debugging: Cek kelas yang tersedia
            $availableKelas = Kelas::where('kode_prodi', $prodi->kode_prodi)
                ->where('angkatan', $request->angkatan)
                ->get();
            Log::info('Available Kelas for prodi ' . $prodi->kode_prodi . ' and angkatan ' . $request->angkatan . ': ', $availableKelas->toArray());

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
                ->flatMap(function ($item) {
                    // Ambil semua mahasiswa terkait dengan tugas akhir ini
                    return $item->mahasiswa->map(function ($mahasiswa) use ($item) {
                        $pembimbing1 = $item->membimbing->where('pembimbing_ke', 1)->first();
                        $pembimbing2 = $item->membimbing->where('pembimbing_ke', 2)->first();
                        $penguji1 = $item->menguji->where('penguji_ke', 1)->first();
                        $penguji2 = $item->menguji->where('penguji_ke', 2)->first();

                        return [
                            'kota' => $item->kota, // Kota dulu
                            'nim' => $mahasiswa->nim ?? '-', // Lalu NIM
                            'nama_mhs' => $mahasiswa->nama_mhs ?? '-', // Lalu Nama Mahasiswa
                            'topik' => $item->topik ?? '-', // Topik
                            'pembimbing_1' => $pembimbing1 ? $pembimbing1->dosen->nama_dosen : '-',
                            'nidn_pembimbing_1' => $pembimbing1 ? $pembimbing1->dosen->nidn : '-',
                            'pembimbing_2' => $pembimbing2 ? $pembimbing2->dosen->nama_dosen : '-',
                            'nidn_pembimbing_2' => $pembimbing2 ? $pembimbing2->dosen->nidn : '-',
                            'penguji_1' => $penguji1 ? $penguji1->dosen->nama_dosen : '-',
                            'nidn_penguji_1' => $penguji1 ? $penguji1->dosen->nidn : '-',
                            'penguji_2' => $penguji2 ? $penguji2->dosen->nama_dosen : '-',
                            'nidn_penguji_2' => $penguji2 ? $penguji2->dosen->nidn : '-', // Perbaikan typo
                        ];
                    });
                })->values();

            Log::info('Generated PDPT data: ', $data->toArray());

            if ($data->isEmpty()) {
                return redirect()->route('data-ta.generate.pdpt.form')
                    ->with('error', 'Tidak ada data untuk program studi dan angkatan yang dipilih. Periksa log untuk detail.');
            }

            $currentDate = now()->format('d-m-Y');
            $filename = "laporan_pdpt_tugas_akhir_{$namaProdi}_{$request->angkatan}_{$currentDate}.pdf";

            $pdf = Pdf::loadView('tugas-akhir-view.laporan-pdpt-ta', compact('data', 'kaprodi', 'prodi'));
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error generating PDPT: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('data-ta.generate.pdpt.form')
                ->with('error', 'Gagal menghasilkan laporan: ' . $e->getMessage);
        }
    }

    public function handleDownloadHonor(Request $request)
    {
        try {
            $request->validate([
                'prodi' => 'required|exists:prodi,kode_prodi',
                'angkatan' => 'required|digits:4|integer',
                'jenis_laporan' => 'required|in:honor,pdpt',
            ]);

            if ($request->jenis_laporan === 'honor') {
                return $this->generateHonorTA($request);
            }

            return redirect()->back()
                ->with('error', 'Jenis laporan tidak valid.');
        } catch (\Exception $e) {
            Log::error('Error handling Honor download: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menangani unduhan Honor: ' . $e->getMessage());
        }
    }

    public function generateHonorTA(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error generating Honor TA: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat laporan Honor TA: ' . $e->getMessage());
        }
    }

    public function displayHonorTA(Request $request)
    {
        logger('Display Honor TA Request:', $request->all());

        try {
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

            return view('tugas-akhir-view.display-honor-ta', compact('data', 'prodi', 'kaprodi', 'tahunAkademik', 'sekretaris'))
                ->with('success', 'Data Honor Tugas Akhir berhasil ditampilkan.');
        } catch (\Exception $e) {
            Log::error('Error displaying Honor TA: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menampilkan data Honor TA: ' . $e->getMessage());
        }
    }

    public function formGenerateHonor()
    {
        try {
            $angkatans = Kelas::select('angkatan')
                ->distinct()
                ->orderBy('angkatan', 'asc')
                ->pluck('angkatan');

            $prodis = Prodi::all();

            return view('tugas-akhir-view.generate-honor-ta', compact('angkatans', 'prodis'))
                ->with('success', 'Formulir Honor berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading Honor form: ' . $e->getMessage());
            return view('tugas-akhir-view.generate-honor-ta')
                ->with('error', 'Gagal memuat formulir Honor: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'angkatan' => 'required|digits:4',
            'prodi' => 'required|exists:prodi,kode_prodi',
        ]);

        try {
            Excel::import(new DataTAImport($request->angkatan, $request->prodi), $request->file('file')->getRealPath(), null, \Maatwebsite\Excel\Excel::XLSX, ['sheet' => 'pembimbing_pdpt']);
            return redirect()->back()
                ->with('success', 'Data Tugas Akhir berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error importing Tugas Akhir data: ' . $e->getMessage(), ['exception' => $e->getTraceAsString(), 'request' => $request->all()]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
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

            return view('tugas-akhir-view.import-excel-ta', compact('angkatans'))
                ->with('success', 'Formulir impor berhasil dimuat.');
        } catch (\Exception $e) {
            Log::error('Error loading import form: ' . $e->getMessage());
            return view('tugas-akhir-view.import-excel-ta')
                ->with('error', 'Gagal memuat formulir impor: ' . $e->getMessage());
        }
    }
}
