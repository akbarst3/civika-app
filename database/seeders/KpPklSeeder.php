<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KpPklSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kp_pkl')->insert([
            [
                'id_perusahaan' => 1,
                'tahun' => 2025,
                'nim' => '202300001',
                'kode_dosen' => 'DSN001',
                'nama_perusahaan' => 'PT Maju Sejahtera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 2,
                'tahun' => 2025,
                'nim' => '202300002',
                'kode_dosen' => 'DSN002',
                'nama_perusahaan' => 'CV Teknologi Nusantara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 3,
                'tahun' => 2025,
                'nim' => '202300003',
                'kode_dosen' => 'DSN003',
                'nama_perusahaan' => 'PT Sukses Mandiri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 4,
                'tahun' => 2025,
                'nim' => '202300004',
                'kode_dosen' => 'DSN001',
                'nama_perusahaan' => 'PT Inovasi Baru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 5,
                'tahun' => 2025,
                'nim' => '202300005',
                'kode_dosen' => 'DSN002',
                'nama_perusahaan' => 'CV Solusi Digital',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 6,
                'tahun' => 2025,
                'nim' => '202300006',
                'kode_dosen' => 'DSN003',
                'nama_perusahaan' => 'PT Jaya Abadi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 7,
                'tahun' => 2025,
                'nim' => '202300007',
                'kode_dosen' => 'DSN004',
                'nama_perusahaan' => 'CV Makmur Sentosa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 8,
                'tahun' => 2025,
                'nim' => '202300008',
                'kode_dosen' => 'DSN005',
                'nama_perusahaan' => 'PT Prima Solusi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 9,
                'tahun' => 2025,
                'nim' => '202300009',
                'kode_dosen' => 'DSN006',
                'nama_perusahaan' => 'CV Teknologi Maju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 10,
                'tahun' => 2025,
                'nim' => '202300010',
                'kode_dosen' => 'DSN001',
                'nama_perusahaan' => 'PT Nusantara Jaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 11,
                'tahun' => 2025,
                'nim' => '202300011',
                'kode_dosen' => 'DSN002',
                'nama_perusahaan' => 'CV Harmoni Sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 12,
                'tahun' => 2025,
                'nim' => '202300012',
                'kode_dosen' => 'DSN003',
                'nama_perusahaan' => 'PT Cemerlang Utama',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
