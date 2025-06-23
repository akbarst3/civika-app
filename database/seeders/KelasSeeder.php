<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            [
                'nama_kelas' => 'A',
                'angkatan' => '2023',
                'kode_prodi' => 1,
                'kode_dosen' => 'KO001N',
            ],
            [
                'nama_kelas' => 'C',
                'angkatan' => '2023',
                'kode_prodi' => 1,
                'kode_dosen' => NULL,
            ],
            [
                'nama_kelas' => 'A',
                'angkatan' => '2023',
                'kode_prodi' => 2,
                'kode_dosen' => NULL,
            ],
        ];

        Kelas::insert($kelas);
    }
}
