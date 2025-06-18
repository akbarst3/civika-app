<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['nama_kelas' => 'A', 'angkatan' => '2023', 'kode_prodi' => '1'],
            ['nama_kelas' => 'B', 'angkatan' => '2023', 'kode_prodi' => '1'],
            ['nama_kelas' => 'C', 'angkatan' => '2023', 'kode_prodi' => '1'],
            ['nama_kelas' => 'A', 'angkatan' => '2023', 'kode_prodi' => '2'],
            ['nama_kelas' => 'B', 'angkatan' => '2023', 'kode_prodi' => '2'],
            ['nama_kelas' => 'B', 'angkatan' => '2021', 'kode_prodi' => '1'],
            ['nama_kelas' => 'A', 'angkatan' => '2021', 'kode_prodi' => '1',],
        ];

        Kelas::insert($kelas);
    }
}
