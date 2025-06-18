<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Models\Membimbing;
use App\Models\Menguji;
use App\Models\Kelas;
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
    private $kodeProdi;

    public function __construct($angkatan, $kodeProdi)
    {
        $this->angkatan = $angkatan;
        $this->kodeProdi = $kodeProdi;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            Log::debug('Processing row ' . ($index + 2) . ': ' . json_encode($row));

            // Map columns based on Excel structure
            $data = [
                'no' => $row[0] ?? null,
                'kota' => $row[1] ?? null, // KoTA
                'nim' => $row[2] ?? null, // NIM
                'anggota_kota' => $row[3] ?? null,
                'topik' => $row[4] ?? null, // Topik Sesuai FTA Sidang
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

            // Handle empty KoTA with stricter validation
            if (empty($data['kota']) || trim($data['kota']) === '') {
                if ($this->lastKota === null) {
                    Log::error("Skipping row " . ($index + 2) . ": Empty or invalid KoTA and no previous KoTA available for NIM " . ($data['nim'] ?? 'unknown'));
                    continue;
                }
                $kota = $this->lastKota;
                Log::warning("Using last KoTA {$kota} for row " . ($index + 2));
            } else {
                $kota = trim($data['kota']);
                $this->lastKota = $kota;
            }

            // Validate NIM
            if (empty($data['nim']) || trim($data['nim']) === '') {
                Log::error("Skipping row " . ($index + 2) . ": NIM is missing or empty");
                continue;
            }

            // Validasi angkatan
            $nimYear = '20' . substr(trim($data['nim']), 0, 2);
            if ($nimYear !== $this->angkatan) {
                Log::error("Skipping row " . ($index + 2) . ": NIM {$data['nim']} angkatan {$nimYear} does not match with requested angkatan {$this->angkatan}");
                continue;
            }

            DB::beginTransaction();
            try {
                // Create or update TugasAkhir
                $tugasAkhir = TugasAkhir::firstOrCreate(
                    ['kota' => $kota],
                    ['topik' => $data['topik'] ?? null, 'created_at' => now(), 'updated_at' => now()]
                );

                // Get or create Kelas based on prodi and angkatan
                $kelas = Kelas::where('kode_prodi', $this->kodeProdi)
                    ->where('angkatan', $this->angkatan)
                    ->first();
                if (!$kelas) {
                    Log::error("Skipping row " . ($index + 2) . ": No Kelas found for Prodi {$this->kodeProdi} and Angkatan {$this->angkatan}");
                    DB::rollBack();
                    continue;
                }

                // Create or update Mahasiswa with kota explicitly
                $mahasiswa = Mahasiswa::firstOrCreate(
                    ['nim' => trim($data['nim'])],
                    [
                        'nama_mhs' => null, // Tambahkan logika jika ada di Excel
                        'kota' => $kota, // Pastikan kota disimpan
                        'kelas_id' => $kelas->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Verify that kota is saved
                $mahasiswa->refresh(); // Refresh model to get the latest data
                if (is_null($mahasiswa->kota) || $mahasiswa->kota !== $kota) {
                    Log::warning("Kota not saved correctly for NIM {$data['nim']}. Expected {$kota}, got {$mahasiswa->kota}. Attempting update.");
                    $mahasiswa->update(['kota' => $kota]); // Force update if not saved
                }
                Log::info("Mahasiswa created/updated for NIM {$data['nim']} with kota {$mahasiswa->kota}");

                // Validate and get Dosen for Pembimbing 1
                if (empty($data['pembimbing_1_nidn'])) {
                    Log::error("Skipping row " . ($index + 2) . ": Pembimbing 1 NIDN is missing for NIM {$data['nim']}");
                    DB::rollBack();
                    continue;
                }
                $pembimbing1 = Dosen::where('nidn', trim($data['pembimbing_1_nidn']))->first();
                if (!$pembimbing1) {
                    Log::error("Skipping row " . ($index + 2) . ": Pembimbing 1 NIDN {$data['pembimbing_1_nidn']} not found in Dosen table for NIM {$data['nim']}");
                    DB::rollBack();
                    continue;
                }

                // Validate and get Dosen for Pembimbing 2
                if (!empty($data['pembimbing_2_nidn'])) {
                    $pembimbing2 = Dosen::where('nidn', trim($data['pembimbing_2_nidn']))->first();
                    if (!$pembimbing2) {
                        Log::error("Skipping row " . ($index + 2) . ": Pembimbing 2 NIDN {$data['pembimbing_2_nidn']} not found in Dosen table for NIM {$data['nim']}");
                        DB::rollBack();
                        continue;
                    }
                } else {
                    $pembimbing2 = null;
                }

                // Validate and get Dosen for Penguji 1
                if (empty($data['penguji_1_nidn'])) {
                    Log::error("Skipping row " . ($index + 2) . ": Penguji 1 NIDN is missing for NIM {$data['nim']}");
                    DB::rollBack();
                    continue;
                }
                $penguji1 = Dosen::where('nidn', trim($data['penguji_1_nidn']))->first();
                if (!$penguji1) {
                    Log::error("Skipping row " . ($index + 2) . ": Penguji 1 NIDN {$data['penguji_1_nidn']} not found in Dosen table for NIM {$data['nim']}");
                    DB::rollBack();
                    continue;
                }

                // Validate and get Dosen for Penguji 2
                if (!empty($data['penguji_2_nidn'])) {
                    $penguji2 = Dosen::where('nidn', trim($data['penguji_2_nidn']))->first();
                    if (!$penguji2) {
                        Log::error("Skipping row " . ($index + 2) . ": Penguji 2 NIDN {$data['penguji_2_nidn']} not found in Dosen table for NIM {$data['nim']}");
                        DB::rollBack();
                        continue;
                    }
                } else {
                    $penguji2 = null;
                }

                // Insert into Membimbing for Pembimbing 1
                Membimbing::firstOrCreate(
                    ['kota' => $kota, 'kode_dosen' => $pembimbing1->kode_dosen, 'pembimbing_ke' => 1],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                // Insert into Membimbing for Pembimbing 2 (if exists)
                if ($pembimbing2) {
                    Membimbing::firstOrCreate(
                        ['kota' => $kota, 'kode_dosen' => $pembimbing2->kode_dosen, 'pembimbing_ke' => 2],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }

                // Insert into Menguji for Penguji 1
                Menguji::firstOrCreate(
                    ['kota' => $kota, 'kode_dosen' => $penguji1->kode_dosen, 'penguji_ke' => 1],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                // Insert into Menguji for Penguji 2 (if exists)
                if ($penguji2) {
                    Menguji::firstOrCreate(
                        ['kota' => $kota, 'kode_dosen' => $penguji2->kode_dosen, 'penguji_ke' => 2],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }

                DB::commit();
                Log::info("Successfully processed row " . ($index + 2) . " for NIM {$data['nim']} with KoTA {$kota}");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process row " . ($index + 2) . " for NIM {$data['nim']}: " . $e->getMessage());
                continue;
            }
        }
    }
}
