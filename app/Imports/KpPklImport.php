<?php

namespace App\Imports;

use App\Models\KpPkl;
use App\Models\MembimbingKpPkl;
use App\Models\MengujiKpPkl;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class KpPklImport implements ToModel
{
    protected string $angkatan;
    protected int $kode_prodi;

    public function __construct($angkatan, $kode_prodi)
    {
        $this->angkatan = $angkatan;
        $this->kode_prodi = $kode_prodi;
        Log::info("Initializing import for angkatan: {$angkatan}, prodi: {$kode_prodi}");
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        if ($row[1] === 'NIM' || $row[3] === 'Nama Perusahaan') {
            Log::info("Skipping header row", $row);
            return null;
        }

        $mappedRow = [
            'no' => $row[0] ?? null,
            'nim' => $row[1] ?? null,
            'nama' => $row[2] ?? null,
            'nama_perusahaan' => $row[3] ?? null,
            'pembimbing_1_nama' => $row[4] ?? null,
            'pembimbing_1_nidn' => $row[5] ?? null,
            'pembimbing_2_nama' => $row[6] ?? null,
            'pembimbing_2_nidn' => $row[7] ?? null,
            'penguji_1_nama' => $row[8] ?? null,
            'penguji_1_nidn' => $row[9] ?? null,
            'penguji_2_nama' => $row[10] ?? null,
            'penguji_2_nidn' => $row[11] ?? null,
        ];

        Log::debug('Processing row', $mappedRow);

        if (empty($mappedRow['nim']) || !preg_match('/^\d{9}$/', (string) $mappedRow['nim']) || empty($mappedRow['nama_perusahaan'])) {
            Log::warning("Skipping row due to invalid NIM or Nama Perusahaan", $mappedRow);
            return null;
        }

        $nim = (string) $mappedRow['nim'];

        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        if (!$mahasiswa) {
            Log::warning("Mahasiswa with NIM {$nim} not found in database", $mappedRow);
            return null;
        }

        Log::debug("Mahasiswa data for NIM {$nim}", [
            'mahasiswa' => $mahasiswa->toArray(),
            'kelas' => $mahasiswa->kelas ? $mahasiswa->kelas->toArray() : null,
        ]);

        if ($mahasiswa->angkatan != $this->angkatan) {
            Log::warning("Angkatan mismatch for NIM {$nim}: expected {$this->angkatan}, got {$mahasiswa->angkatan}", $mappedRow);
            return null;
        }

        if (!$mahasiswa->kelas) {
            Log::warning("Kelas not found for NIM {$nim}", $mappedRow);
            return null;
        }

        $mahasiswaProdi = $mahasiswa->kelas->kode_prodi;
        if ($mahasiswaProdi != $this->kode_prodi) {
            Log::warning("Prodi mismatch for NIM {$nim}: expected {$this->kode_prodi}, got {$mahasiswaProdi}", $mappedRow);
            return null;
        }

        if (KpPkl::where('nim', $nim)->where('tahun', $this->angkatan)->exists()) {
            Log::warning("Duplicate NIM: {$nim} for tahun: {$this->angkatan}, skipping row");
            return null;
        }

        $existingPerusahaan = KpPkl::where('nama_perusahaan', $mappedRow['nama_perusahaan'])->first();

        if ($existingPerusahaan) {
            $id_perusahaan = $existingPerusahaan->id_perusahaan;
        } else {
            $id_perusahaan = KpPkl::max('id_perusahaan') + 1 ?: 1;
        }

        try {
            $kpPkl = KpPkl::create([
                'id_perusahaan' => $id_perusahaan,
                'tahun' => $this->angkatan,
                'nim' => $nim,
                'nama_perusahaan' => $mappedRow['nama_perusahaan'],
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Failed to create KpPkl record: {$e->getMessage()}", $mappedRow);
            return null;
        }

        $cleanNidn = function ($nidn) {
            return is_numeric($nidn) ? (string) (int) $nidn : trim((string) $nidn);
        };

        if (!empty($mappedRow['pembimbing_1_nidn'])) {
            $nidn1 = $cleanNidn($mappedRow['pembimbing_1_nidn']);
            Log::debug("Checking Pembimbing 1 with NIDN: {$nidn1}");
            $dosen1 = Dosen::where('nidn', $nidn1)->first();
            if ($dosen1) {
                try {
                    MembimbingKpPkl::create([
                        'id_perusahaan' => $id_perusahaan,
                        'tahun' => $this->angkatan,
                        'kode_dosen' => $dosen1->kode_dosen,
                        'pembimbing_ke' => 1,
                    ]);
                    Log::info("Created MembimbingKpPkl record for Pembimbing 1, NIDN: {$nidn1}");
                } catch (\Illuminate\Database\QueryException $e) {
                    Log::error("Failed to create MembimbingKpPkl record for Pembimbing 1: {$e->getMessage()}", $mappedRow);
                }
            } else {
                Log::warning("Dosen with NIDN {$nidn1} not found for Pembimbing 1");
            }
        } else {
            Log::warning("Missing or empty Pembimbing 1 NIDN", ['pembimbing_1_nidn' => $mappedRow['pembimbing_1_nidn']]);
        }

        if (!empty($mappedRow['pembimbing_2_nidn'])) {
            $nidn2 = $cleanNidn($mappedRow['pembimbing_2_nidn']);
            Log::debug("Checking Pembimbing 2 with NIDN: {$nidn2}");
            $dosen2 = Dosen::where('nidn', $nidn2)->first();
            if ($dosen2) {
                try {
                    MembimbingKpPkl::create([
                        'id_perusahaan' => $id_perusahaan,
                        'tahun' => $this->angkatan,
                        'kode_dosen' => $dosen2->kode_dosen,
                        'pembimbing_ke' => 2,
                    ]);
                    Log::info("Created MembimbingKpPkl record for Pembimbing 2, NIDN: {$nidn2}");
                } catch (\Illuminate\Database\QueryException $e) {
                    Log::error("Failed to create MembimbingKpPkl record for Pembimbing 2: {$e->getMessage()}", $mappedRow);
                }
            } else {
                Log::warning("Dosen with NIDN {$nidn2} not found for Pembimbing 2");
            }
        } else {
            Log::warning("Missing or empty Pembimbing 2 NIDN", ['pembimbing_2_nidn' => $mappedRow['pembimbing_2_nidn']]);
        }

        if (!empty($mappedRow['penguji_1_nidn'])) {
            $nidn3 = $cleanNidn($mappedRow['penguji_1_nidn']);
            Log::debug("Checking Penguji 1 with NIDN: {$nidn3}");
            $dosen3 = Dosen::where('nidn', $nidn3)->first();
            if ($dosen3) {
                try {
                    MengujiKpPkl::create([
                        'id_perusahaan' => $id_perusahaan,
                        'tahun' => $this->angkatan,
                        'kode_dosen' => $dosen3->kode_dosen,
                        'penguji_ke' => 1,
                    ]);
                    Log::info("Created MengujiKpPkl record for Penguji 1, NIDN: {$nidn3}");
                } catch (\Illuminate\Database\QueryException $e) {
                    Log::error("Failed to create MengujiKpPkl record for Penguji 1: {$e->getMessage()}", $mappedRow);
                }
            } else {
                Log::warning("Dosen with NIDN {$nidn3} not found for Penguji 1");
            }
        } else {
            Log::warning("Missing or empty Penguji 1 NIDN", ['penguji_1_nidn' => $mappedRow['penguji_1_nidn']]);
        }

        if (!empty($mappedRow['penguji_2_nidn'])) {
            $nidn4 = $cleanNidn($mappedRow['penguji_2_nidn']);
            Log::debug("Checking Penguji 2 with NIDN: {$nidn4}");
            $dosen4 = Dosen::where('nidn', $nidn4)->first();
            if ($dosen4) {
                try {
                    MengujiKpPkl::create([
                        'id_perusahaan' => $id_perusahaan,
                        'tahun' => $this->angkatan,
                        'kode_dosen' => $dosen4->kode_dosen,
                        'penguji_ke' => 2,
                    ]);
                    Log::info("Created MengujiKpPkl record for Penguji 2, NIDN: {$nidn4}");
                } catch (\Illuminate\Database\QueryException $e) {
                    Log::error("Failed to create MengujiKpPkl record for Penguji 2: {$e->getMessage()}", $mappedRow);
                }
            } else {
                Log::warning("Dosen with NIDN {$nidn4} not found for Penguji 2");
            }
        } else {
            Log::warning("Missing or empty Penguji 2 NIDN", ['penguji_2_nidn' => $mappedRow['penguji_2_nidn']]);
        }

        return $kpPkl;
    }
}