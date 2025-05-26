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
            ],
            [
                'nama_kelas' => 'C',
                'angkatan' => '2023',
                'kode_prodi' => 1,
            ],
            [
                'nama_kelas' => 'A',
                'angkatan' => '2023',
                'kode_prodi' => 2,
            ],
        ];

        Kelas::insert($kelas);
    }
}