<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Models\Membimbing;
use App\Models\Menguji;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DataTAImport implements ToCollection, WithStartRow, WithChunkReading
{
    private $lastKota = null;
    private $angkatan;

    public function __construct($angkatan, $prodi)
    {
        $this->angkatan = $angkatan;
        $this->kode_prodi = $prodi;
    }


    /**
     * Specify the starting row for the import (row 2 as header).
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Specify chunk size for reading the Excel file.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Process the collection of rows.
     *
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            Log::debug('Processing row ' . ($index + 2) . ': ' . json_encode($row));

            // Map columns explicitly based on index
            $data = [
                'no' => $row[0] ?? null,
                'kota' => $row[1] ?? null,
                'nim' => $row[2] ?? null,
                'anggota_kota' => $row[3] ?? null,
                'topik_sesuai_fta_sidang' => $row[4] ?? null,
                'tempat' => $row[5] ?? null,
                'pembimbing_1_nama' => $row[6] ?? null,
                'pembimbing_1_nidn' => $row[7] ?? null,
                'pembimbing_2_nama' => $row[8] ?? null,
                'pembimbing_2_nidn' => $row[9] ?? null,
                'penguji_1_nama' => $row[10] ?? null,
                'penguji_1_nidn' => $row[11] ?? null,
                'penguji_2_nama' => $row[12] ?? null,
                'penguji_2_nidn' => $row[13] ?? null,
            ];

            // Handle empty KoTA
            if (empty($data['kota'])) {
                if ($this->lastKota === null) {
                    Log::error("Skipping row " . ($index + 2) . ": Empty KoTA and no previous KoTA available for NIM " . ($data['nim'] ?? 'unknown'));
                    continue;
                }
                $kota = $this->lastKota;
            } else {
                $kota = $data['kota'];
                $this->lastKota = $kota;
            }

            // Validate NIM
            if (empty($data['nim'])) {
                Log::error("Skipping row " . ($index + 2) . ": NIM is missing or empty");
                continue;
            }

            // Validasi angkatan
            $nimYear = '20' . substr($data['nim'], 0, 2);
            if ($nimYear !== $this->angkatan) {
                Log::error("Skipping row " . ($index + 2) . ": NIM {$data['nim']} angkatan {$nimYear} does not match with requested angkatan {$this->angkatan}");
                continue;
            }

            $mahasiswa = Mahasiswa::where('nim', $data['nim'])->first();
            $mahasiswaProdi = $mahasiswa->kelas->kode_prodi;
            if ($mahasiswaProdi != $this->kode_prodi) {
                Log::warning("Prodi mismatch for NIM {$data['nim']}: expected {$this->kode_prodi}, got {$mahasiswaProdi}");
                continue;
            }
            if (!$mahasiswa) {
                Log::error("Skipping row " . ($index + 2) . ": NIM {$data['nim']} not found in Mahasiswa table");
                continue;
            }

            // Validate Pembimbing 1 NIDN
            if (empty($data['pembimbing_1_nidn'])) {
                Log::error("Skipping row " . ($index + 2) . ": Pembimbing 1 NIDN is missing for NIM {$data['nim']}");
                continue;
            }
            $pembimbing1 = Dosen::where('nidn', $data['pembimbing_1_nidn'])->first();
            if (!$pembimbing1) {
                Log::error("Skipping row " . ($index + 2) . ": Pembimbing 1 NIDN {$data['pembimbing_1_nidn']} not found in Dosen table for NIM {$data['nim']}");
                continue;
            }

            // Validate Pembimbing 2 NIDN
            if (empty($data['pembimbing_2_nidn'])) {
                Log::error("Skipping row " . ($index + 2) . ": Pembimbing 2 NIDN is missing for NIM {$data['nim']}");
                continue;
            }
            $pembimbing2 = Dosen::where('nidn', $data['pembimbing_2_nidn'])->first();
            if (!$pembimbing2) {
                Log::error("Skipping row " . ($index + 2) . ": Pembimbing 2 NIDN {$data['pembimbing_2_nidn']} not found in Dosen table for NIM {$data['nim']}");
                continue;
            }

            // Validate Penguji 1 NIDN
            if (empty($data['penguji_1_nidn'])) {
                Log::error("Skipping row " . ($index + 2) . ": Penguji 1 NIDN is missing for NIM {$data['nim']}");
                continue;
            }
            $penguji1 = Dosen::where('nidn', $data['penguji_1_nidn'])->first();
            if (!$penguji1) {
                Log::error("Skipping row " . ($index + 2) . ": Penguji 1 NIDN {$data['penguji_1_nidn']} not found in Dosen table for NIM {$data['nim']}");
                continue;
            }

            // Validate Penguji 2 NIDN
            if (empty($data['penguji_2_nidn'])) {
                Log::error("Skipping row " . ($index + 2) . ": Penguji 2 NIDN is missing for NIM {$data['nim']}");
                continue;
            }
            $penguji2 = Dosen::where('nidn', $data['penguji_2_nidn'])->first();
            if (!$penguji2) {
                Log::error("Skipping row " . ($index + 2) . ": Penguji 2 NIDN {$data['penguji_2_nidn']} not found in Dosen table for NIM {$data['nim']}");
                continue;
            }

            DB::beginTransaction();
            try {
                // Create or update TugasAkhir
                $tugasAkhir = TugasAkhir::firstOrCreate(
                    ['kota' => $kota],
                    [
                        'topik' => $data['topik_sesuai_fta_sidang']
                    ]
                );

                $mahasiswa = Mahasiswa::updateOrCreate(
                    ['nim' => $data['nim']],
                    [
                        'kota' => $kota
                    ]
                );

                // Insert into Membimbing for Pembimbing 1
                Membimbing::firstOrCreate([
                    'kota' => $kota,
                    'kode_dosen' => $pembimbing1->kode_dosen,
                    'pembimbing_ke' => 1,
                ]);

                // Insert into Membimbing for Pembimbing 2
                Membimbing::firstOrCreate([
                    'kota' => $kota,
                    'kode_dosen' => $pembimbing2->kode_dosen,
                    'pembimbing_ke' => 2,
                ]);

                // Insert into Menguji for Penguji 1
                Menguji::firstOrCreate([
                    'kota' => $kota,
                    'kode_dosen' => $penguji1->kode_dosen,
                    'penguji_ke' => 1,
                ]);

                // Insert into Menguji for Penguji 2
                Menguji::firstOrCreate([
                    'kota' => $kota,
                    'kode_dosen' => $penguji2->kode_dosen,
                    'penguji_ke' => 2,
                ]);

                DB::commit();
                Log::info("Successfully processed row " . ($index + 2) . " for NIM {$data['nim']} with KoTA {$kota}");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process row " . ($index + 2) . " for NIM {$data['nim']}: " . $e->getMessage());
                continue;
            }

//            // Create or update TugasAkhir
//            $tugasAkhir = TugasAkhir::firstOrCreate(
//                ['kota' => $kota],
//                [
//                    'topik' => $data['topik_sesuai_fta_sidang'],
//                    'nim' => $data['nim'],
//                ]
//            );
//
//            // Insert into Membimbing for Pembimbing 1
//            Membimbing::firstOrCreate([
//                'kota' => $kota,
//                'kode_dosen' => $pembimbing1->kode_dosen,
//                'pembimbing_ke' => 1,
//            ]);
//
//            // Insert into Membimbing for Pembimbing 2
//            Membimbing::firstOrCreate([
//                'kota' => $kota,
//                'kode_dosen' => $pembimbing2->kode_dosen,
//                'pembimbing_ke' => 2,
//            ]);
//
//            // Insert into Menguji for Penguji 1
//            Menguji::firstOrCreate([
//                'kota' => $kota,
//                'kode_dosen' => $penguji1->kode_dosen,
//                'penguji_ke' => 1,
//            ]);
//
//            // Insert into Menguji for Penguji 2
//            Menguji::firstOrCreate([
//                'kota' => $kota,
//                'kode_dosen' => $penguji2->kode_dosen,
//                'penguji_ke' => 2,
//            ]);
//
//            Log::info("Successfully processed row " . ($index + 2) . " for NIM {$data['nim']} with KoTA {$kota}");
        }
    }
}
