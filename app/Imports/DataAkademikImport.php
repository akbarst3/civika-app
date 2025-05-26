<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\IndeksPrestasiSemester;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomImportExceptions\DataAlreadyExistsException;
use App\Exceptions\CustomImportExceptions\InvalidExcelStructureException;

class DataAkademikImport implements ToCollection, WithMultipleSheets, SkipsUnknownSheets
{
    // Indeks dimulai dari 0
    private const PROGRAM_INFO_ROW = 5;
    private const SEMESTER_INFO_ROW = 4;
    private const KODE_DOSEN_HEADER_ROW = 10;
    private const KODE_MATKUL_HEADER_ROW = 11;
    private const DATA_MAHASISWA_START_ROW = 13; 

    private const NIM_COL = 1; 
    private const MATKUL_START_COL = 3; 

    private const PROGRAM_D3 = 'DIPLOMA 3';
    private const PROGRAM_D4 = 'SARJANA TERAPAN';

    private $programStudiGlobal;
    private $semesterGlobal;
    private $matkulHeader = [];
    private $jumlahDColumnMap = [];
    private $validDosenCodes = [];
    private $validMatkulCodes = [];
    private $validMahasiswaNims = [];
    private $existingIPSKeys = [];
    private $existingAbsensiKeys = [];
    private $existingNilaiKeys = [];
    private $ipsData = [];
    private $absensiData = [];
    private $nilaiData = [];
    private $nimColumnIndex;
    private $matkulStartColumnIndex;
    private $lastMatkulColumnIndex;
    private $lastJumlahDColumnIndex;
    private $nilaiBobotIPSColumnIndex;
    private $ipColumnIndex;
    private $jumlahSakitColumnIndex;
    private $jumlahIzinColumnIndex;
    private $jumlahAlfaColumnIndex;
    private $nilaiPenghayatanColumnIndex;
    private $statusIPSColumnIndex;
    private $keteranganColumnIndex;

    private $sheetName;

    public function __construct($sheetName = null)
    {
        $this->sheetName = $sheetName;
        $this->nimColumnIndex = self::NIM_COL;
        $this->matkulStartColumnIndex = self::MATKUL_START_COL;
    }

    public function sheets(): array
    {
        if ($this->sheetName) {
            Log::info("Scheduling single sheet: {$this->sheetName}");
            return [$this->sheetName => $this];
        }
        $sheetNames = ['A', 'B', 'C'];
        $sheets = [];
        foreach ($sheetNames as $sheetName) {
            $sheets[$sheetName] = new self($sheetName);
            Log::info("Scheduling sheet: {$sheetName}");
        }
        return $sheets;
    }

    public function onUnknownSheet($sheetName)
    {
        Log::warning("Sheet '{$sheetName}' tidak ditemukan, dilewati.");
    }

    public function collection(Collection $rows)
    {
        Log::info("Processing sheet: {$this->sheetName}");
        $this->determineFormatExcel($rows);
        $this->extractGlobalInfo($rows);
        $this->prefetchSupportingData();
        $this->prefetchExistingRecords($rows);
        $this->processStudentRows($rows);
        $this->saveData();
    }

    private function determineFormatExcel(Collection $rows) {
        // Mengambil informasi dari Header
        $programInfoRowData = $rows[self::PROGRAM_INFO_ROW] ?? null;
        if ($programInfoRowData && isset($programInfoRowData[0])) {
            $programName = strtoupper(trim($programInfoRowData[0]));
            if (strpos($programName, self::PROGRAM_D3) !== false) {
                $this->programStudiGlobal = self::PROGRAM_D3;
            } elseif (strpos($programName, self::PROGRAM_D4) !== false) {
                $this->programStudiGlobal = self::PROGRAM_D4;
            }
        }
        if (!$this->programStudiGlobal) {
            throw new InvalidExcelStructureException("Program studi (DIPLOMA 3/SARJANA TERAPAN) tidak ditemukan pada baris " . (self::PROGRAM_INFO_ROW + 1) . " di sheet {$this->sheetName}.");
        }
        Log::info("Importer: Program Studi terdeteksi di sheet {$this->sheetName}: {$this->programStudiGlobal}");

        // Mengambil informasi Mata Kuliah dan Dosen
        $dosenHeaderRow = $rows[self::KODE_DOSEN_HEADER_ROW] ?? collect();
        $matkulHeaderRow = $rows[self::KODE_MATKUL_HEADER_ROW] ?? collect();

        // Log::debug("Konten mentah matkulHeaderRow: " . json_encode($matkulHeaderRow->toArray())); 
        // Log::debug("Konten mentah dosenHeaderRow: " . json_encode($dosenHeaderRow->toArray())); 

        $this->matkulHeader = [];
        $this->lastMatkulColumnIndex = $this->matkulStartColumnIndex - 1;
        $initialEmptyTolerance = 3;
        $maxMatkulColumnsToScan = 10;

        for ($i = 0; $i < $maxMatkulColumnsToScan; $i++) { 
            $col = $this->matkulStartColumnIndex + $i;

            $kodeMatkul = trim($matkulHeaderRow[$col] ?? '');
            $kodeDosen = trim($dosenHeaderRow[$col] ?? '');

            $isValidLookingMatkulCode = !empty($kodeMatkul) && preg_match('/[a-zA-Z]/', $kodeMatkul);

            if ($isValidLookingMatkulCode) {
                $this->matkulHeader[$col] = ['kode_matkul' => $kodeMatkul, 'kode_dosen' => $kodeDosen ?: null];
                $this->lastMatkulColumnIndex = $col;
            } else {
                if (!empty($this->matkulHeader)) {
                    break;
                } else {
                    if ($i >= ($initialEmptyTolerance - 1) ) { 
                        break;
                    }
                }
            }
        }

        if (empty($this->matkulHeader)) {
            Log::warning("Importer: Tidak ada kolom mata kuliah yang terdeteksi dari header.");
            throw new InvalidExcelStructureException('Tidak ada kolom mata kuliah yang terdeteksi dari header.');
        } else {
            Log::info("Importer: Kolom mata kuliah terdeteksi dari indeks {$this->matkulStartColumnIndex} sampai {$this->lastMatkulColumnIndex}. Jumlah: " . count($this->matkulHeader));
        }

        $currentColumnIndex = $this->lastMatkulColumnIndex + 1;
        $maxSemesterForJumlahD = ($this->programStudiGlobal === self::PROGRAM_D4) ? 8 : 6;
        for ($s = 1; $s <= $maxSemesterForJumlahD; $s++) {
            $this->jumlahDColumnMap[$s] = $currentColumnIndex;
            $currentColumnIndex++;
        }

        $this->lastJumlahDColumnIndex = $currentColumnIndex - 1;
        Log::info("Importer: Kolom 'Jumlah D' dipetakan: " . json_encode($this->jumlahDColumnMap) . ". Kolom terakhir Jumlah D: {$this->lastJumlahDColumnIndex}");

        // Mengambil informasi kolom-kolom lainnya
        $idx = $this->lastJumlahDColumnIndex + 2;
        $this->nilaiBobotIPSColumnIndex = $idx++;
        $idx++; 
        $this->ipColumnIndex = $idx++; 
        $idx++; 
        $this->jumlahSakitColumnIndex = $idx++;
        $this->jumlahIzinColumnIndex = $idx++; 
        $this->jumlahAlfaColumnIndex = $idx++; 
        $idx++; 
        $this->nilaiPenghayatanColumnIndex = $idx++;
        $idx++;
        $this->statusIPSColumnIndex = $idx++; 
        $idx++; 
        $this->keteranganColumnIndex = $idx++;

        Log::info("Importer: Tata Letak Kolom Dinamis: IP={$this->ipColumnIndex}, NilaiBobot={$this->nilaiBobotIPSColumnIndex}, StatusIPS={$this->statusIPSColumnIndex}, JumlahSakit={$this->jumlahSakitColumnIndex}, Keterangan={$this->keteranganColumnIndex}");
    }

    private function extractGlobalInfo(Collection $rows) {
        $semesterInfoRowData = $rows[self::SEMESTER_INFO_ROW] ?? null;
        if ($semesterInfoRowData && isset($semesterInfoRowData[0])) {
            if (preg_match('/SEMESTER\s*:\s*(\d+)/i', $semesterInfoRowData[0], $matches)) {
                $this->semesterGlobal = trim($matches[1]);
            }
        }
        if (!$this->semesterGlobal) {
            throw new InvalidExcelStructureException('Semester global tidak ditemukan pada baris ' . (self::SEMESTER_INFO_ROW + 1) . '.');
        }
        Log::info("Importer: Semester Global terdeteksi: {$this->semesterGlobal}");

        if ($this->programStudiGlobal === self::PROGRAM_D3 && (int)$this->semesterGlobal > 6) {
            throw new InvalidExcelStructureException("Semester {$this->semesterGlobal} tidak valid untuk program {$this->programStudiGlobal} (Maksimal 6).");
        } elseif ($this->programStudiGlobal === self::PROGRAM_D4 && (int)$this->semesterGlobal > 8) {
            throw new InvalidExcelStructureException("Semester {$this->semesterGlobal} tidak valid untuk program {$this->programStudiGlobal} (Maksimal 8).");
        }
    }

    private function prefetchSupportingData()
    {
        $uniqueDosenCodes = collect($this->matkulHeader)->pluck('kode_dosen')->filter()->unique()->values()->all();
        $uniqueMatkulCodes = collect($this->matkulHeader)->pluck('kode_matkul')->filter()->unique()->values()->all();

        if (!empty($uniqueDosenCodes)) {
            $this->validDosenCodes = Dosen::whereIn('kode_dosen', $uniqueDosenCodes)->pluck('kode_dosen')->toArray();
        }
        if (!empty($uniqueMatkulCodes)) {
            $this->validMatkulCodes = MataKuliah::whereIn('kode_matkul', $uniqueMatkulCodes)->pluck('kode_matkul')->toArray();
        }
        Log::info("Importer: Valid Dosen Codes loaded: " . count($this->validDosenCodes));
        Log::info("Importer: Valid Matkul Codes loaded: " . count($this->validMatkulCodes));
    }

    private function prefetchExistingRecords(Collection $rows)
    {
        $allNimsInSheet = [];
        for ($k = self::DATA_MAHASISWA_START_ROW; $k < count($rows); $k++) {
            $nimInRow = trim($rows[$k][$this->nimColumnIndex] ?? '');
            if (!empty($nimInRow)) {
                $allNimsInSheet[] = $nimInRow;
            }
        }
        $allNimsInSheet = array_unique($allNimsInSheet);

        if (empty($allNimsInSheet)) return;

        $this->validMahasiswaNims = Mahasiswa::whereIn('nim', $allNimsInSheet)->pluck('nim')->toArray();

        $existingIPSRecords = IndeksPrestasiSemester::whereIn('nim', $allNimsInSheet)
            ->where('semester', $this->semesterGlobal)->select('nim', 'semester')->get();
        foreach ($existingIPSRecords as $record) {
            $this->existingIPSKeys[$record->nim . '_' . $record->semester] = true;
        }

        $existingAbsensiRecords = Absensi::whereIn('nim', $allNimsInSheet)
            ->where('semester', $this->semesterGlobal)->select('nim', 'semester')->get();
        foreach ($existingAbsensiRecords as $record) {
            $this->existingAbsensiKeys[$record->nim . '_' . $record->semester] = true;
        }

        $nimsForNilaiQuery = array_intersect($allNimsInSheet, $this->validMahasiswaNims);
        if (!empty($nimsForNilaiQuery) && !empty($this->validDosenCodes) && !empty($this->validMatkulCodes)) {
            $existingNilaiRecords = Nilai::whereIn('nim', $nimsForNilaiQuery)
                ->where('semester_ke', $this->semesterGlobal)
                ->whereIn('kode_dosen', $this->validDosenCodes)
                ->whereIn('kode_matkul', $this->validMatkulCodes)
                ->select('nim', 'kode_dosen', 'kode_matkul')->get();
            foreach ($existingNilaiRecords as $record) {
                $this->existingNilaiKeys["{$record->nim}_{$record->kode_matkul}_{$record->kode_dosen}"] = true;
            }
        }
    }

    private function processStudentRows(Collection $rows)
    {
        for ($i = self::DATA_MAHASISWA_START_ROW; $i < count($rows); $i++) {
            $rowData = $rows[$i];
            if (empty($rowData[$this->nimColumnIndex])) continue;

            $nim = trim($rowData[$this->nimColumnIndex]);
            if (!$this->validateMahasiswa($nim, $i)) continue;

            $this->prepareIPSData($rowData, $nim);
            $this->prepareAbsensiData($rowData, $nim);
            $this->prepareNilaiData($rowData, $nim);
        }
    }

    private function saveData()
    {
        DB::beginTransaction();
        try {
            if (!empty($this->ipsData)) IndeksPrestasiSemester::insert($this->ipsData);
            if (!empty($this->absensiData)) Absensi::insert($this->absensiData);
            if (!empty($this->nilaiData)) Nilai::insert($this->nilaiData);
            DB::commit();
            Log::info('Importer: Proses impor selesai. Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Importer: Gagal menyimpan data: {$e->getMessage()} \n" . $e->getTraceAsString());
            throw $e;
        }
    }

    private function validateMahasiswa($nim, $rowIndex): bool
    {
        if (!in_array($nim, $this->validMahasiswaNims)) {
            Log::warning("Importer: Mahasiswa NIM {$nim} (baris Excel ".($rowIndex+1).") tidak ditemukan. Data dilewati.");
            return false;
        }
        return true;
    }

    private function prepareIPSData($rowData, $nim)
    {
        $ipsKey = $nim . '_' . $this->semesterGlobal;
        if (isset($this->existingIPSKeys[$ipsKey])) {
            throw new DataAlreadyExistsException("Data Indeks Prestasi Semester untuk NIM {$nim} semester {$this->semesterGlobal} sudah ada di database.");
        }

        $jumlahDValue = 0;
        if (isset($this->jumlahDColumnMap[(int)$this->semesterGlobal])) {
            $jumlahDColIdx = $this->jumlahDColumnMap[(int)$this->semesterGlobal];
            $jumlahDValue = $rowData[$jumlahDColIdx] ?? 0;
        } 

        $ipExcel = $rowData[$this->ipColumnIndex] ?? null;
        $ipProcessed = null;
        if ($ipExcel !== null && trim((string)$ipExcel) !== '') {
            $ipString = str_replace(',', '.', (string)$ipExcel);
            if (is_numeric($ipString)) {
                $ipFloat = (float)$ipString;
                $ipProcessed = round($ipFloat, 2);
                if (!($ipProcessed >= 0.00 && $ipProcessed <= 4.00)) {
                    Log::warning("Importer: IP {$ipExcel} (NIM {$nim}) di luar rentang (0-4) setelah proses. Disimpan null.");
                    $ipProcessed = null;
                }
            } else {
                Log::warning("Importer: IP {$ipExcel} (NIM {$nim}) bukan numerik. Disimpan null.");
            }
        }

        $this->ipsData[] = [
            'nim'             => $nim,
            'semester'        => $this->semesterGlobal,
            'status'          => $rowData[$this->statusIPSColumnIndex] ?? null,
            'indeks_prestasi' => $ipProcessed,
            'nilai_bobot'     => $rowData[$this->nilaiBobotIPSColumnIndex] ?? 0,
            'jumlah_d'        => $jumlahDValue,
            'keterangan'      => $rowData[$this->keteranganColumnIndex] ?? null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }

    private function prepareAbsensiData($rowData, $nim)
    {
        $absensiKey = $nim . '_' . $this->semesterGlobal;
        if (isset($this->existingAbsensiKeys[$absensiKey])) {
            throw new DataAlreadyExistsException("Data Absensi untuk NIM {$nim} semester {$this->semesterGlobal} sudah ada di database.");
        }
        $this->absensiData[] = [
            'nim'               => $nim,
            'semester'          => $this->semesterGlobal,
            'jml_sakit'         => $rowData[$this->jumlahSakitColumnIndex] ?? 0,
            'jml_izin'          => $rowData[$this->jumlahIzinColumnIndex] ?? 0,
            'jml_alfa'          => $rowData[$this->jumlahAlfaColumnIndex] ?? 0,
            'nilai_penghayatan' => $rowData[$this->nilaiPenghayatanColumnIndex] ?? null,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    private function prepareNilaiData($rowData, $nim)
    {
        foreach ($this->matkulHeader as $columnIndex => $headerInfo) {
            $kodeMatkul = $headerInfo['kode_matkul'];
            $kodeDosen = $headerInfo['kode_dosen'];
            $indeksNilai = $rowData[$columnIndex] ?? null;

            if ($kodeMatkul && $indeksNilai !== null && trim((string)$indeksNilai) !== '') {
                if (empty($kodeDosen)) { 
                     Log::warning("Importer: Kode Dosen kosong untuk Matkul {$kodeMatkul} (NIM {$nim}). Nilai dilewati.");
                     continue;
                }
                if (!in_array($kodeDosen, $this->validDosenCodes)) {
                    Log::warning("Importer: Kode Dosen {$kodeDosen} tidak valid (Matkul {$kodeMatkul}, NIM {$nim}). Nilai dilewati.");
                    continue;
                }
                if (!in_array($kodeMatkul, $this->validMatkulCodes)) {
                    Log::warning("Importer: Kode Matkul {$kodeMatkul} tidak valid (Dosen {$kodeDosen}, NIM {$nim}). Nilai dilewati.");
                    continue;
                }

                $nilaiKey = "{$nim}_{$kodeMatkul}_{$kodeDosen}";
                if (isset($this->existingNilaiKeys[$nilaiKey])) {
                    throw new DataAlreadyExistsException("Data Nilai untuk NIM {$nim}, Mata Kuliah {$kodeMatkul}, Dosen {$kodeDosen}, Semester {$this->semesterGlobal} sudah ada di database.");
                }
                $this->nilaiData[] = [
                    'kode_dosen'  => $kodeDosen,
                    'kode_matkul' => $kodeMatkul,
                    'nim'         => $nim,
                    'indeks_nilai'=> $indeksNilai,
                    'semester_ke' => $this->semesterGlobal,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }
    }
}