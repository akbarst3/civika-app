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
        $tahun = $request->input('tahun', '2025');
        $semester = $request->input('semester', 3);
        $kelas = $request->input('kelas_id');
        $program_studi = $request->input('program_studi', 1);

        $tahun_akademik = $tahun . '/' . ($tahun + 1);

        // data prodi untuk dropdown
        $prodis = Prodi::all();
        // data kelas untuk dropdown
        $kelasList = Kelas::all();
        // data matkul untuk dropdown
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semester) {
            $query->select('kode_matkul')->from('nilai')->where('semester_ke', $semester);
        })->get();

        Log::info('MataKuliahs:', ['count' => $mataKuliahs->count(), 'data' => $mataKuliahs->pluck('kode_matkul')->toArray()]);

        $mahasiswas = Mahasiswa::with([
            'kelas.prodi',
            'nilai' => function ($query) {
                $query->with(['mataKuliah', 'dosen']);
            },
            'absensi' => function ($query) use ($semester) {
                $query->where('semester', $semester);
            },
            'indeksPrestasiSemester' => function ($query) use ($semester) {
                $query->whereIn('semester', [$semester, $semester - 1]);
            },
        ])->get();

        $totalSemesters = IndeksPrestasiSemester::select('semester')->distinct()->count();

        $data = $mahasiswas->map(function ($mhs, $index) use ($semester) {
            $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);

            $totalSks = $nilaiSemester->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $jumlah_d = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester)->jumlah_d ?? 0;
            $totalSksD = $mhs->indeksPrestasiSemester->sum('jumlah_d');
            $ipSekarang = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->indeks_prestasi ?? 0;
            $ipLalu = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester - 1))->indeks_prestasi ?? 0;
            $nilai_bobot = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->nilai_bobot ?? 0;

            $totalBobot = $mhs->indeksPrestasiSemester->sum('nilai_bobot');
            $totalSksAll = $mhs->nilai->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $ipk = $totalSksAll > 0 ? round($totalBobot / $totalSksAll, 2) : 0;

            $absensi = $mhs->absensi->first();
            $jml = $absensi ? $absensi->jml_sakit + $absensi->jml_izin + $absensi->jml_alfa : 0;

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
            // $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);
            // $nilaiDetail = $nilaiSemester->keyBy('kode_matkul');

            return [
                'no' => $index + 1,
                'nim' => $mhs->nim,
                'nama_mhs' => $mhs->nama_mhs,
                'nilai_per_matkul' => $nilaiDetail,
                'total_sks' => $totalSks,
                'jumlah_d' => $jumlah_d,
                'sks_d' => $totalSksD,
                'semester_sks' => $semesterSks->toArray(),
                'nilai_bobot' => $nilai_bobot,
                'ip_semester' => ['lalu' => $ipLalu, 'sekarang' => $ipSekarang],
                'ipk' => $ipk,
                'jml_sakit' => $absensi->jml_sakit ?? 0,
                'jml_izin' => $absensi->jml_izin ?? 0,
                'jml_alfa' => $absensi->jml_alfa ?? 0,
                'jml' => $jml,
                'nilai_penghayatan' => $absensi->nilai_penghayatan ?? '-',
                'status' => optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->status ?? 'N/A',
                'keterangan' => optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->keterangan ?? '-',
            ];
        });

        return view('buku-besar-view.tabel-buku-besar', compact('data', 'totalSemesters', 'tahun_akademik', 'semester', 'mataKuliahs', 'prodis', 'kelas', 'tahun', 'program_studi'));

        // dd($data);
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
        $semester = $request->input('semester', 3); // Default ke semester 3

        // Ambil daftar mata kuliah untuk semester tertentu
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semester) {
            $query->select('kode_matkul')
                  ->from('nilai')
                  ->where('semester_ke', $semester);
        })->get();

        // Ambil data mahasiswa dengan nilai untuk semester tertentu
        $mahasiswas = Mahasiswa::with([
            'nilai' => function ($query) use ($semester) {
                $query->where('semester_ke', $semester)
                      ->with(['mataKuliah', 'dosen']);
            }
        ])->get();

        // Transformasi data untuk view
        $data = $mahasiswas->filter(function ($mhs) {
            return $mhs->nilai->isNotEmpty(); // Hanya sertakan mahasiswa dengan nilai
        })->map(function ($mhs, $index) use ($semester) {
            $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);

            $nilaiDetail = $nilaiSemester->map(function ($n) {
                return [
                    'kode_matkul' => $n->kode_matkul,
                    'nama_matkul' => optional($n->mataKuliah)->nama_matkul ?? '-',
                    'kode_dosen' => optional($n->dosen)->kode_dosen ?? '-',
                    'nama_dosen' => optional($n->dosen)->nama_dosen ?? '-',
                    'jumlah_sks' => optional($n->mataKuliah)->jumlah_sks ?? 0,
                    'indeks_nilai' => $n->indeks_nilai ?? '-',
                ];
            })->values();

            return [
                'no' => $index + 1,
                'nim' => $mhs->nim,
                'nama_mhs' => $mhs->nama_mhs,
                'nilai_per_matkul' => $nilaiDetail,
            ];
        })->values();

        return view('buku-besar-view.tabel-buku-besar-dosen', compact('data', 'semester', 'mataKuliahs'));
    }

    public function bukuBesarWaliMahasiswa(Request $request)
    {
        $tahun = $request->input('tahun', '2025');
        $semester = $request->input('semester', 3);
        $kelas = $request->input('kelas_id');
        $program_studi = $request->input('program_studi', 1);

        $tahun_akademik = $tahun . '/' . ($tahun + 1);

        $prodis = Prodi::all();
        $kelasList = Kelas::all();
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semester) {
            $query->select('kode_matkul')->from('nilai')->where('semester_ke', $semester);
        })->get();

        Log::info('MataKuliahs:', ['count' => $mataKuliahs->count(), 'data' => $mataKuliahs->pluck('kode_matkul')->toArray()]);

        $mahasiswas = Mahasiswa::with([
            'kelas.prodi',
            'nilai' => function ($query) {
                $query->with(['mataKuliah', 'dosen']);
            },
            'absensi' => function ($query) use ($semester) {
                $query->where('semester', $semester);
            },
            'indeksPrestasiSemester' => function ($query) use ($semester) {
                $query->whereIn('semester', [$semester, $semester - 1]);
            },
        ])->get();

        $totalSemesters = IndeksPrestasiSemester::select('semester')->distinct()->count();

        $data = $mahasiswas->map(function ($mhs, $index) use ($semester) {
            $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);

            $totalSks = $nilaiSemester->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $jumlah_d = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester)->jumlah_d ?? 0;
            $totalSksD = $mhs->indeksPrestasiSemester->sum('jumlah_d');
            $ipSekarang = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->indeks_prestasi ?? 0;
            $ipLalu = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester - 1))->indeks_prestasi ?? 0;
            $nilai_bobot = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->nilai_bobot ?? 0;

            $totalBobot = $mhs->indeksPrestasiSemester->sum('nilai_bobot');
            $totalSksAll = $mhs->nilai->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $ipk = $totalSksAll > 0 ? round($totalBobot / $totalSksAll, 2) : 0;

            $absensi = $mhs->absensi->first();
            $jml = $absensi ? $absensi->jml_sakit + $absensi->jml_izin + $absensi->jml_alfa : 0;

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
                'jumlah_d' => $jumlah_d,
                'sks_d' => $totalSksD,
                'semester_sks' => $semesterSks->toArray(),
                'nilai_bobot' => $nilai_bobot,
                'ip_semester' => ['lalu' => $ipLalu, 'sekarang' => $ipSekarang],
                'ipk' => $ipk,
                'jml_sakit' => $absensi->jml_sakit ?? 0,
                'jml_izin' => $absensi->jml_izin ?? 0,
                'jml_alfa' => $absensi->jml_alfa ?? 0,
                'jml' => $jml,
                'nilai_penghayatan' => $absensi->nilai_penghayatan ?? '-',
                'status' => optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->status ?? 'N/A',
                'keterangan' => optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->keterangan ?? '-',
            ];
        });

        return view('buku-besar-view.tabel-buku-besar-wali-mahasiswa', compact('data', 'totalSemesters', 'tahun_akademik', 'semester', 'mataKuliahs', 'prodis', 'kelas', 'tahun', 'program_studi'));
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
