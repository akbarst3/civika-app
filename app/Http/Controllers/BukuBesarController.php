<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Imports\DataAkademikImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IndeksPrestasiSemester;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Exceptions\CustomImportExceptions\DataAlreadyExistsException;
use App\Exceptions\CustomImportExceptions\InvalidExcelStructureException;


class BukuBesarController extends Controller
{

    public function showUploadForm()
    {
        return view('buku-besar-view.import-buku-besar');
    }

    public function importExcel(Request $request)
    {
        Log::info('ImportBukuBesarController@importExcel method dipanggil.');
        Log::debug('Data Request: ', $request->all());
        $validator = Validator::make($request->all(), [
            'excel_files' => 'required|array',
            'excel_files.*' => 'required|mimes:xlsx,xls,csv|max:20480' // Max 20MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan tidak valid. Pastikan file sesuai format dan ukuran maksimum.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->hasFile('excel_files') || count($request->file('excel_files')) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang dipilih untuk diunggah.'
            ], 400);
        }

        $files = $request->file('excel_files');
        $results = [];
        $totalSheets = 0;
        $processedSheets = 0;

        foreach ($files as $file) {
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheetNames = $spreadsheet->getSheetNames();
                $totalSheets += count(array_intersect(['A', 'B', 'C'], $sheetNames));
            } catch (\Exception $e) {
                Log::error("Importer: Failed to load sheet names for file {$file->getClientOriginalName()}: " . $e->getMessage());
                continue;
            }
        }

        foreach ($files as $file) {
            $originalFileName = $file->getClientOriginalName();
            Log::info("Importer: Processing file: {$originalFileName}");

            $fileResult = [
                'fileName' => $originalFileName,
                'sheets' => []
            ];

            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $actualSheetNames = $spreadsheet->getSheetNames();
            } catch (\Exception $e) {
                $fileResult['sheets'][] = [
                    'sheetName' => 'N/A',
                    'success' => false,
                    'message' => "Gagal memuat file: " . $e->getMessage()
                ];
                $results[] = $fileResult;
                Log::error("Importer: Failed to load file {$originalFileName}: " . $e->getMessage());
                continue;
            }

            $expectedSheetNames = ['A', 'B', 'C'];

            foreach ($expectedSheetNames as $sheetName) {
                if (!in_array($sheetName, $actualSheetNames)) {
                    continue; // Skip if sheet doesn't exist
                }

                $sheetResult = [
                    'sheetName' => $sheetName,
                    'success' => false,
                    'message' => 'Terjadi kesalahan tidak diketahui saat memproses sheet.'
                ];

                try {
                    // Process one sheet at a time
                    Excel::import(new DataAkademikImport($sheetName), $file);
                    $sheetResult['success'] = true;
                    $sheetResult['message'] = "Sheet {$sheetName} berhasil diimpor.";
                    Log::info("Importer: Sheet '{$sheetName}' in file '{$originalFileName}' berhasil diimpor.");
                } catch (DataAlreadyExistsException $e) {
                    $sheetResult['message'] = "Gagal impor sheet {$sheetName}: Data sudah ada. Detail: " . $e->getMessage();
                    Log::warning("Importer: DataAlreadyExistsException for sheet {$sheetName} in {$originalFileName}: " . $e->getMessage());
                } catch (InvalidExcelStructureException $e) {
                    $sheetResult['message'] = "Gagal impor sheet {$sheetName}: Struktur Excel tidak sesuai. Detail: " . $e->getMessage();
                    Log::warning("Importer: InvalidExcelStructureException for sheet {$sheetName} in {$originalFileName}: " . $e->getMessage());
                } catch (ValidationException $e) {
                    $failures = $e->failures();
                    $errorString = "Validasi gagal untuk sheet {$sheetName} di file '{$originalFileName}': ";
                    $detailedErrors = [];
                    foreach ($failures as $failure) {
                        $attribute = $failure->attribute();
                        $value = is_array($failure->values()) && isset($failure->values()[$attribute]) ? $failure->values()[$attribute] : (!is_array($failure->values()) ? $failure->values() : '');
                        $detailedErrors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . " (Nilai: {$value}). ";
                    }
                    $sheetResult['message'] = "Gagal impor karena struktur Excel tidak sesuai (kesalahan validasi data). Detail: " . implode(" ", $detailedErrors);
                    Log::warning("Importer: ValidationException for sheet {$sheetName} in {$originalFileName}: " . $sheetResult['message']);
                } catch (\Maatwebsite\Excel\Exceptions\SheetNotFoundException $e) {
                    $sheetResult['message'] = "Gagal impor: Sheet {$sheetName} tidak ditemukan.";
                    Log::error("Importer: SheetNotFoundException for sheet {$sheetName} in {$originalFileName}: " . $e->getMessage());
                } catch (\Exception $e) {
                    $sheetResult['message'] = "Gagal impor sheet {$sheetName}: Kesalahan tidak terduga. Detail: " . $e->getMessage();
                    Log::error("Importer: Generic Error importing sheet {$sheetName} in {$originalFileName}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                }

                $fileResult['sheets'][] = $sheetResult;
                $processedSheets++;
            }

            $results[] = $fileResult;
        }

        return response()->json([
            'success' => true,
            'totalSheets' => $totalSheets,
            'processedSheets' => $processedSheets,
            'results' => $results
        ]);
    }

    public function bukuBesar(Request $request)
    {
        // 1. Ambil angkatan terakhir yang memiliki data indeks prestasi
        $angkatanTerakhir = Kelas::whereIn('id', function ($query) {
            $query->select('kelas_id')->from('mahasiswa')->whereIn('nim', function ($sub) {
                $sub->select('nim')->from('indeks_prestasi_semester');
            });
        })->max('angkatan');

        // 2. Ambil semester terakhir dari angkatan tersebut
        $semesterTerakhir = IndeksPrestasiSemester::whereIn('nim', function ($query) use ($angkatanTerakhir) {
            $query->select('nim')
                ->from('mahasiswa')
                ->whereIn('kelas_id', function ($subquery) use ($angkatanTerakhir) {
                    $subquery->select('id')
                        ->from('kelas')
                        ->where('angkatan', $angkatanTerakhir);
                });
        })->max('semester') ?? 1;

        // 3. Ambil prodi default: D3
        $defaultProdi = Prodi::where('nama_prodi', 'D3')->first();

        // 4. Ambil kelas A dari prodi D3 dan angkatan terakhir
        $defaultKelas = Kelas::where([
            ['nama_kelas', 'A'],
            ['kode_prodi', $defaultProdi->kode_prodi ?? 0],
            ['angkatan', $angkatanTerakhir]
        ])->first();

        // 5. Gunakan nilai dari request atau fallback ke default
        $tahun = $request->input('tahun', $angkatanTerakhir);
        $semester = $request->input('semester', $semesterTerakhir);
        $kelas = $request->input('kelas_id', $defaultKelas->id ?? null);
        $program_studi = $request->input('program_studi', $defaultProdi->kode_prodi ?? null);
        $tahun_akademik = $tahun . '/' . ($tahun + 1);

        // 6. Data referensi
        $prodis = Prodi::all();
        $kelasList = Kelas::with('prodi')->get();
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semester) {
            $query->select('kode_matkul')->from('nilai')->where('semester_ke', $semester);
        })
        ->get();
        // dd($mataKuliahs);

        // 7. Query mahasiswa dengan relasi
        $mahasiswaQuery = Mahasiswa::with([
            'kelas.prodi',
            'nilai' => fn($query) => $query->with(['mataKuliah', 'dosen']),
            'absensi' => fn($query) => $query->where('semester', $semester),
            'indeksPrestasiSemester' => fn($query) => $query->whereIn('semester', [$semester, $semester - 1]),
        ]);

        // Filter berdasarkan kelas atau prodi dan tahun
        if ($kelas) {
            $mahasiswaQuery->where('kelas_id', $kelas);
        } else {
            $mahasiswaQuery->whereHas('kelas', function ($query) use ($program_studi, $tahun) {
                if ($program_studi) {
                    $query->where('kode_prodi', $program_studi);
                }
                if ($tahun) {
                    $query->where('angkatan', $tahun);
                }
            });
        }

        $mahasiswas = $mahasiswaQuery->get();
        $totalSemesters = IndeksPrestasiSemester::select('semester')->distinct()->count();

        // 8. Proses data per mahasiswa
        $data = $mahasiswas
        ->filter(function ($mhs) use ($semester) {
            return
                $mhs->nilai->where('semester_ke', $semester)->isNotEmpty() ||
                $mhs->indeksPrestasiSemester->where('semester', $semester)->isNotEmpty() ||
                $mhs->absensi->isNotEmpty();
        })
        ->values()
        ->map(function ($mhs, $index) use ($semester) {
            $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);
            $ipSmtNow = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester);
            $ipSmtBefore = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester - 1);
            $absensi = $mhs->absensi->first();

            $totalSks = $nilaiSemester->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $totalSksAll = $mhs->nilai->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $totalBobot = $mhs->indeksPrestasiSemester->sum('nilai_bobot');
            $jumlahD = $ipSmtNow->jumlah_d ?? 0;
            $sksD = $mhs->indeksPrestasiSemester->sum('jumlah_d');
            $ipNow = $ipSmtNow->indeks_prestasi ?? 0;
            $ipPrev = $ipSmtBefore->indeks_prestasi ?? 0;
            $nilaiBobot = $ipSmtNow->nilai_bobot ?? 0;
            $ipk = $totalSksAll > 0 ? round($totalBobot / $totalSksAll, 2) : 0;
            $ipAverage = round($mhs->indeksPrestasiSemester->pluck('indeks_prestasi')->avg(), 2);

            $jml_sakit = $absensi->jml_sakit ?? 0;
            $jml_izin = $absensi->jml_izin ?? 0;
            $jml_alfa = $absensi->jml_alfa ?? 0;
            $jml = $jml_sakit + $jml_izin + $jml_alfa;

            $semesterSks = collect(range(1, 8))->mapWithKeys(fn($s) => [
                $s => $mhs->nilai->where('semester_ke', $s)->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0)
            ]);

            $nilaiDetail = $nilaiSemester->map(fn($n) => [
                'kode_matkul' => $n->kode_matkul,
                'nama_matkul' => $n->mataKuliah->nama_matkul ?? '-',
                'kode_dosen' => $n->dosen->kode_dosen ?? '-',
                'nama_dosen' => $n->dosen->nama_dosen ?? '-',
                'jumlah_sks' => $n->mataKuliah->jumlah_sks ?? 0,
                'indeks_nilai' => $n->indeks_nilai ?? '-',
            ])->values();

            return [
                'no' => $index + 1,
                'nim' => $mhs->nim,
                'nama_mhs' => $mhs->nama_mhs,
                'nilai_per_matkul' => $nilaiDetail,
                'total_sks' => $totalSks,
                'jumlah_d' => $jumlahD,
                'sks_d' => $sksD,
                'semester_sks' => $semesterSks->toArray(),
                'nilai_bobot' => $nilaiBobot,
                'ip_semester' => [
                    'lalu' => $ipPrev,
                    'sekarang' => $ipNow
                ],
                'ipk' => $ipk,
                'jml_sakit' => $jml_sakit,
                'jml_izin' => $jml_izin,
                'jml_alfa' => $jml_alfa,
                'jml' => $jml,
                'nilai_penghayatan' => $absensi->nilai_penghayatan ?? '-',
                'status' => $ipSmtNow->status ?? 'N/A',
                'keterangan' => $ipSmtNow->keterangan ?? '-',
            ];
        });

        return view('buku-besar-view.tabel-buku-besar', compact(
            'data',
            'totalSemesters',
            'tahun_akademik',
            'semester',
            'mataKuliahs',
            'prodis',
            'kelasList',
            'kelas',
            'tahun',
            'program_studi'
        ));
    }

    public function cekStatus(Request $request)
    {
        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->month;

        // Hitung default tahun akademik aktif
        if ($bulanSekarang >= 7) {
            $tahunAwal = $tahunSekarang;
            $semesterLabelDefault = 'Ganjil';
        } else {
            $tahunAwal = $tahunSekarang - 1;
            $semesterLabelDefault = 'Genap';
        }
        $tahunAkademikDefault = "$tahunAwal/" . ($tahunAwal + 1) . " $semesterLabelDefault";

        // Ambil dari request atau gunakan default
        $tahunAkademikAktif = $request->input('tahun_akademik', $tahunAkademikDefault);

        // Parse input tahun akademik
        [$tahunPeriode, $ganjilGenap] = explode(' ', $tahunAkademikAktif); // "2023/2024 Genap" → ['2023/2024', 'Genap']
        [$tahunMulai, $tahunSelesai] = explode('/', $tahunPeriode);
        $tahunMulai = (int)$tahunMulai;

        // Daftar tahun akademik tersedia
        $angkatanTerkecil = Kelas::min('angkatan');
        $tahunAkademikFilter = [];
        if ($angkatanTerkecil) {
            for ($tahun = (int)$angkatanTerkecil; $tahun <= $tahunAwal; $tahun++) {
                $tahunAkademikFilter[] = "$tahun/" . ($tahun + 1);
            }
        }

        $kelas = Kelas::with('prodi')->get();

        $importStatus = $kelas->map(function ($kls) use ($tahunMulai, $ganjilGenap) {
            $angkatan = (int)$kls->angkatan;
            $semesterAktif = ($tahunMulai - $angkatan) * 2 + ($ganjilGenap === 'Ganjil' ? 1 : 2);

            // Maksimal semester
            $prodi = strtolower($kls->prodi->nama_prodi ?? '');
            $maksSemester = str_contains($prodi, 'd3') ? 6 : 8;

            if ($semesterAktif < 1 || $semesterAktif > $maksSemester) {
                return null;
            }

            $tingkat_kelas = (int) ceil($semesterAktif / 2);

            // Ambil mahasiswa dan indeks prestasi
            $mahasiswa = $kls->mahasiswa()->with([
                'indeksPrestasiSemester' => fn($q) => $q->where('semester', $semesterAktif)
            ])->get();

            $sudahDiisi = $mahasiswa->pluck('indeksPrestasiSemester')->flatten()->isNotEmpty();

            return [
                'nama_kelas' => $kls->nama_kelas,
                'angkatan' => $angkatan,
                'nama_prodi' => $kls->prodi->nama_prodi ?? '-',
                'tingkat' => $tingkat_kelas,
                'semester_aktif' => $semesterAktif,
                'status' => $sudahDiisi ? 'imported' : 'not_imported',
                'kelas_id' => $kls->id,
                'kode_prodi' => $kls->prodi->kode_prodi ?? null,
            ];

        })->filter()->sortBy([

            fn ($a, $b) => $a['tingkat'] <=> $b['tingkat'],
            fn ($a, $b) => strcmp($a['nama_prodi'], $b['nama_prodi']),
            fn ($a, $b) => strcmp($a['nama_kelas'], $b['nama_kelas']),
            fn ($a, $b) => $b['angkatan'] <=> $a['angkatan'],
            fn ($a, $b) => $a['semester_aktif'] <=> $b['semester_aktif'],
            fn ($a, $b) => strcmp($a['status'], $b['status']),
        ]);

        return view('buku-besar-view.status-import', [
            'importStatus' => $importStatus,
            'tahunAkademikFilter' => $tahunAkademikFilter,
            'tahunAkademikAktif' => $tahunAkademikAktif,
        ]);
    }

    public function bukuBesarDosen(Request $request)
    {
        $kodeDosen = $request->input('kode_dosen', 'KO001N'); // default kode dosen, diganti ke auth dosen nanti
        $semester = $request->input('semester', 3); // Default ke semester 3
        $kodeMatkul = $request->input('kode_matkul'); // Ambil dari request, bisa null jika tidak dipilih

        // Logika tahun akademik dari cekStatus
        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->month;

        // Hitung default tahun akademik aktif
        if ($bulanSekarang >= 7) {
            $tahunAwal = $tahunSekarang;
            $semesterLabelDefault = 'Ganjil';
        } else {
            $tahunAwal = $tahunSekarang - 1;
            $semesterLabelDefault = 'Genap';
        }
        $tahunAkademikDefault = "$tahunAwal/" . ($tahunAwal + 1) . " $semesterLabelDefault";

        // Ambil dari request atau gunakan default
        $tahunAkademikAktif = $request->input('tahun_akademik', $tahunAkademikDefault);

        // Parse input tahun akademik
        [$tahunPeriode, $ganjilGenap] = explode(' ', $tahunAkademikAktif);
        [$tahunMulai, $tahunSelesai] = explode('/', $tahunPeriode);
        $tahunMulai = (int)$tahunMulai;

        // Daftar tahun akademik tersedia
        $angkatanTerkecil = Kelas::min('angkatan');
        $tahunAkademikFilter = [];
        if ($angkatanTerkecil) {
            for ($tahun = (int)$angkatanTerkecil; $tahun <= $tahunAwal; $tahun++) {
                $tahunAkademikFilter[] = "$tahun/" . ($tahun + 1) . " Ganjil";
                $tahunAkademikFilter[] = "$tahun/" . ($tahun + 1) . " Genap";
            }
        }

        // Tentukan semester berdasarkan tahun akademik aktif
        $semesterAktif = ($tahunSekarang - $tahunMulai) * 2 + ($ganjilGenap === 'Ganjil' ? 1 : 2);

        // Ambil daftar mata kuliah yang diajar dosen untuk semester tertentu
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semesterAktif, $kodeDosen) {
            $query->select('kode_matkul')
                ->from('nilai')
                ->where('kode_dosen', $kodeDosen)
                ->where('semester_ke', $semesterAktif);
        })->get();

        // Jika tidak ada mata kuliah, kembalikan data kosong
        if ($mataKuliahs->isEmpty()) {
            return view('buku-besar-view.tabel-buku-besar-dosen', [
                'data' => collect(),
                'semesterAktif' => $semesterAktif,
                'mataKuliahs' => $mataKuliahs,
                'tahunAkademikFilter' => $tahunAkademikFilter,
                'tahunAkademikAktif' => $tahunAkademikAktif,
                'kodeMatkulAktif' => null,
                'matkulAktifData' => null,
            ]);
        }

        // Tentukan mata kuliah default (misalnya yang pertama)
        $defaultKodeMatkul = $mataKuliahs->first()->kode_matkul;
        if (!$kodeMatkul) {
            $kodeMatkul = $defaultKodeMatkul; // Set default jika tidak ada input
        }

        // Ambil semua nilai mahasiswa untuk mata kuliah yang diajar dosen
        $nilaiList = Nilai::with(['mahasiswa.kelas.prodi', 'mataKuliah', 'dosen'])
            ->where('kode_dosen', $kodeDosen)
            ->where('semester_ke', $semesterAktif)
            ->where('kode_matkul', $kodeMatkul) // << penting!
            ->get();
            // ->groupBy('nim');

        // Transformasi data mahasiswa berdasarkan semua nilai mata kuliah
        $data = $nilaiList->map(function ($nilai, $index) {
            return [
                'no' => $index + 1,
                'nim' => $nilai->mahasiswa->nim,
                'nama_mhs' => $nilai->mahasiswa->nama_mhs,
                'kelas' => $nilai->mahasiswa->kelas->nama_kelas ?? '-',
                'program_studi' => $nilai->mahasiswa->kelas->prodi->nama_prodi ?? '-',
                'nilai_per_matkul' => [[
                    'kode_matkul' => $nilai->kode_matkul,
                    'nama_matkul' => $nilai->mataKuliah->nama_matkul ?? '-',
                    'jumlah_sks' => $nilai->mataKuliah->jumlah_sks ?? 0,
                    'indeks_nilai' => $nilai->indeks_nilai ?? '-',
                ]],
            ];
        });

        return view('buku-besar-view.tabel-buku-besar-dosen', [
            'data' => $data,
            'semesterAktif' => $semesterAktif,
            'mataKuliahs' => $mataKuliahs,
            'tahunAkademikFilter' => $tahunAkademikFilter,
            'tahunAkademikAktif' => $tahunAkademikAktif,
            'kodeMatkulAktif' => $kodeMatkul,
            'matkulAktifData' => $mataKuliahs->where('kode_matkul', $kodeMatkul)->first(),
        ]);
    }

    public function bukuBesarWaliMahasiswa(Request $request)
    {
        $kodeDosen = 'KO001N';

        // Ambil kelas dan prodi yang diwalikan oleh dosen
        $kelas = Kelas::where('kode_dosen', $kodeDosen)->with('prodi')->firstOrFail();

        // Ambil semester
        $semester = (int) $request->input('semester', 1);

        // Mata kuliah yang muncul di semester ini
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semester) {
            $query->select('kode_matkul')->from('nilai')->where('semester_ke', $semester);
        })->get();

        // Ambil semua prodi (kemungkinan untuk ditampilkan di tampilan akhir)
        $prodis = Prodi::all();

        // Ambil semua mahasiswa di kelas wali ini, beserta data terkait
        $mahasiswas = Mahasiswa::where('kelas_id', $kelas->id)
            ->with([
                'kelas.prodi',
                'nilai' => fn($query) => $query->with(['mataKuliah', 'dosen']),
                'absensi' => fn($query) => $query->where('semester', $semester),
                'indeksPrestasiSemester' => fn($query) => $query->whereIn('semester', [$semester, $semester - 1]),
            ])
            ->get();

        // Tentukan jumlah semester berdasarkan jenjang prodi
        $kodeProdi = $kelas->kode_prodi;
        $totalSemesters = match ($kodeProdi) {
            1 => 6, // D3
            2 => 8, // D4
            default => 8,
        };

        // Olah data mahasiswa
        $data = $mahasiswas->map(function ($mhs, $index) use ($semester, $totalSemesters) {
            $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);

            // Data indeks prestasi semester sekarang & sebelumnya
            $ipSekarangData = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester);
            $ipLaluData = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester - 1);

            // Data absensi semester ini
            $absensi = $mhs->absensi->first();
            $jml_sakit = $absensi->jml_sakit ?? 0;
            $jml_izin = $absensi->jml_izin ?? 0;
            $jml_alfa = $absensi->jml_alfa ?? 0;
            $nilai_penghayatan = $absensi->nilai_penghayatan ?? '-';
            $jml = $jml_sakit + $jml_izin + $jml_alfa;

            // Perhitungan nilai & IP
            $totalSks = $nilaiSemester->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $totalSksAll = $mhs->nilai->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $totalBobot = $mhs->indeksPrestasiSemester->sum('nilai_bobot');
            $ipk = $totalSksAll > 0 ? round($totalBobot / $totalSksAll, 2) : 0;

            // Rekap SKS per semester
            $semesterSks = collect(range(1, $totalSemesters))->mapWithKeys(fn($s) => [
                $s => $mhs->nilai->where('semester_ke', $s)->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0)
            ]);

            // Detail nilai per mata kuliah semester ini
            $nilaiDetail = $nilaiSemester->map(fn($n) => [
                'kode_matkul' => $n->kode_matkul,
                'nama_matkul' => $n->mataKuliah->nama_matkul ?? '-',
                'kode_dosen' => $n->dosen->kode_dosen ?? '-',
                'nama_dosen' => $n->dosen->nama_dosen ?? '-',
                'jumlah_sks' => $n->mataKuliah->jumlah_sks ?? 0,
                'indeks_nilai' => $n->indeks_nilai ?? '-',
            ])->values();

            return [
                'no' => $index + 1,
                'nim' => $mhs->nim,
                'nama_mhs' => $mhs->nama_mhs,
                'nilai_per_matkul' => $nilaiDetail,
                'total_sks' => $totalSks,
                'jumlah_d' => $ipSekarangData->jumlah_d ?? 0,
                'sks_d' => $mhs->indeksPrestasiSemester->sum('jumlah_d'),
                'semester_sks' => $semesterSks->toArray(),
                'nilai_bobot' => $ipSekarangData->nilai_bobot ?? 0,
                'ip_semester' => [
                    'lalu' => $ipLaluData->indeks_prestasi ?? 0,
                    'sekarang' => $ipSekarangData->indeks_prestasi ?? 0,
                ],
                'ipk' => $ipk,
                'jml_sakit' => $jml_sakit,
                'jml_izin' => $jml_izin,
                'jml_alfa' => $jml_alfa,
                'jml' => $jml,
                'nilai_penghayatan' => $nilai_penghayatan,
                'status' => $ipSekarangData->status ?? 'N/A',
                'keterangan' => $ipSekarangData->keterangan ?? '-',
            ];
        });

        return view('buku-besar-view.tabel-buku-besar-wali-mahasiswa', compact(
            'data', 'totalSemesters', 'semester', 'mataKuliahs', 'prodis', 'kelas'
        ));

    }

    public function generateLaporan(Request $request)
    {
        Log::info('Fungsi generateLaporan dipanggil.', $request->all());

        $validator = Validator::make($request->all(), [
            'mahasiswa_nim' => 'required|string|exists:mahasiswa,nim',
            'format' => 'required|in:pdf,excel',
            'sertakan_nilai_matkul' => 'nullable|present',
            'semester_nilai' => 'required_if:sertakan_nilai_matkul,true|integer',
            'sertakan_ip_kelas' => 'nullable|present',
            'sertakan_ipk_kelas' => 'nullable|present',
        ]);

        if ($validator->fails()) {
            Log::error('validasi generateLaporan gagal.', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nim = $request->input('mahasiswa_nim');
        $format = $request->input('format');
        $semester = (int) $request->input('semester_nilai');

        $mahasiswa = Mahasiswa::with(['kelas.prodi', 'nilai.mataKuliah', 'indeksPrestasiSemester', 'absensi'])
            ->where('nim', $nim)->firstOrFail();

        $totalBobotAll = $mahasiswa->indeksPrestasiSemester->sum('nilai_bobot');
        $totalSksAll = $mahasiswa->nilai->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
        $ipk = $totalSksAll > 0 ? round($totalBobotAll / $totalSksAll, 2) : 0;

        $data = [
            'mahasiswa' => $mahasiswa,
            'ipk' => $ipk,
            'current_date' => Carbon::now()->isoFormat('D MMMM YYYY'),
        ];

        if ($request->input('sertakan_nilai_matkul')) {
            $nilaiSemester = $mahasiswa->nilai->where('semester_ke', $semester);

            $data['include_nilai_semester'] = true;
            $data['semester'] = $semester;
            $data['nilai_semester'] = $nilaiSemester->map(fn($n) => [
                'nama_matkul' => $n->mataKuliah->nama_matkul ?? '-',
                'sks' => $n->mataKuliah->jumlah_sks ?? 0,
                'nilai' => $n->indeks_nilai ?? '-',
            ])->values();

            $data['total_sks_semester'] = $nilaiSemester->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $data['ip_semester'] = optional($mahasiswa->indeksPrestasiSemester->firstWhere('semester', $semester))->indeks_prestasi ?? 0;
        }

        if ($request->input('sertakan_ip_kelas') && $request->input('sertakan_nilai_matkul')) {
            $mahasiswaSatuKelas = Mahasiswa::where('kelas_id', $mahasiswa->kelas_id)
                ->with(['indeksPrestasiSemester' => fn($q) => $q->where('semester', $semester)])
                ->get();

            $totalIpKelas = 0;
            $jumlahMahasiswaDenganIp = 0;
            foreach ($mahasiswaSatuKelas as $mhs) {
                $ips = $mhs->indeksPrestasiSemester->first();
                if ($ips) {
                    $totalIpKelas += $ips->indeks_prestasi;
                    $jumlahMahasiswaDenganIp++;
                }
            }

            $data['include_ip_kelas'] = true;
            $data['rata_rata_ip_kelas'] = $jumlahMahasiswaDenganIp > 0 ? round($totalIpKelas / $jumlahMahasiswaDenganIp, 2) : 0;
        }

        if ($request->input('sertakan_ipk_kelas')) {
            $mahasiswaSatuKelas = Mahasiswa::where('kelas_id', $mahasiswa->kelas_id)
                ->with('indeksPrestasiSemester')
                ->get();

            $totalIpk = 0;
            $jumlahMahasiswa = 0;

            foreach ($mahasiswaSatuKelas as $mhs) {
                $ipAverage = round($mhs->indeksPrestasiSemester->pluck('indeks_prestasi')->avg(), 2);
                if (!is_nan($ipAverage)) {
                    $totalIpk += $ipAverage;
                    $jumlahMahasiswa++;
                }
            }

            $data['include_ipk_kelas'] = true;
            $data['rata_rata_ipk_kelas'] = $jumlahMahasiswa > 0 ? round($totalIpk / $jumlahMahasiswa, 2) : 0;
        }

        $fileName = 'Laporan_Akademik_' . $mahasiswa->nim . '_' . $mahasiswa->nama_mhs . '.' . $format;

        if ($format == 'pdf') {

            Log::info("membuat PDF untuk {$fileName} dengan data:", $data);

            $pdf = Pdf::loadView('buku-besar-view.laporan-mahasiswa', $data); // Kita akan buat view ini
            return $pdf->download($fileName);

            Log::info("PDF berhasil");
        }

        if ($format == 'excel') {
            // return Excel::download(new LaporanMahasiswaExport($data), $fileName);
            return response()->json(['message' => 'fungsi export Excel dalam proses'], 501);
        }

            return redirect()->back()->with('error', 'Format file tidak valid.');
    }
}
