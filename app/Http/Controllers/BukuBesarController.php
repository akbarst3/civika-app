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

    public function cekStatus()
    {
        $semester = '2024/2025_genap';

        $kelas = Kelas::with([
            'prodi',
            'mahasiswa' => function ($query) use ($semester) {
                $query->with([
                    'absensi' => fn($q) => $q->where('semester', $semester),
                    'indeksPrestasiSemester' => fn($q) => $q->where('semester', $semester),
                ]);
            }
        ])->get();

        // cek status
        $importStatus = $kelas->map(function ($kls) {
            $imported = $kls->mahasiswa->isNotEmpty();

            $tahun_sekarang = Carbon::now()->year;
            $tingkat_kelas = $tahun_sekarang - (int)$kls->angkatan;

            return [
                'nama_kelas' => $kls->nama_kelas,
                'angkatan' => $tingkat_kelas,
                'nama_prodi' => $kls->prodi->nama_prodi ?? '-',
                'status' => $imported ? 'imported' : 'not_imported',
            ];
        });

        return view('buku-besar-view.status-import', compact('importStatus'));

    }
}
