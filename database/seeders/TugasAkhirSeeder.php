<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasAkhirSeeder extends Seeder
{
    public function run(): void
    {
        $tugas_akhir = [
            [
                'kota' => 'TA00001',
                'topik' => 'Analisis Machine Learning untuk Prediksi Data',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kota' => 'TA00002',
                'topik' => 'Sistem Informasi Berbasis Web untuk E-Commerce',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kota' => 'TA00003',
                'topik' => 'Optimasi Jaringan 5G menggunakan AI',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('tugas_akhir')->insert($tugas_akhir);

        // 3. Seed tabel mahasiswa (9 mahasiswa, 3 per tugas akhir)
        $mahasiswa = [
            // Tugas Akhir 1 (Google)
            [
                'nim' => '210000001',
                'nama_mhs' => 'Aldrin Rayhan Putra',
                'no_ktp' => '1234567890123401',
                'email' => 'aldrin.rayhan@example.com',
                'telepon' => '081234567801',
                'tgl_lahir' => '2000-01-15',
                'kota_lahir' => 'Jakarta',
                'jenis_kelamin' => 1,
                'agama' => 'Islam',
                'gol_darah' => 'A',
                'anak_ke' => 1,
                'nama_slta' => 'SMA Negeri 1',
                'jalur_daftar' => 'SNBT',
                'nem' => 85.50,
                'kelas_id' => 1,
                'kota' => 'TA00001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '210000002',
                'nama_mhs' => 'Ananta Destawardhana',
                'no_ktp' => '1234567890123402',
                'email' => 'ananta.desta@example.com',
                'telepon' => '081234567802',
                'tgl_lahir' => '2001-03-22',
                'kota_lahir' => 'Bandung',
                'jenis_kelamin' => 1,
                'agama' => 'Kristen',
                'gol_darah' => 'B',
                'anak_ke' => 2,
                'nama_slta' => 'SMA Negeri 2',
                'jalur_daftar' => 'SNBP',
                'nem' => 88.75,
                'kelas_id' => 1,
                'kota' => 'TA00001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '210000003',
                'nama_mhs' => 'M. Fatur Maulidan Azzahra',
                'no_ktp' => '1234567890123403',
                'email' => 'fatur.maulidan@example.com',
                'telepon' => '081234567803',
                'tgl_lahir' => '2000-07-10',
                'kota_lahir' => 'Surabaya',
                'jenis_kelamin' => 1,
                'agama' => 'Islam',
                'gol_darah' => 'O',
                'anak_ke' => 1,
                'nama_slta' => 'SMA Negeri 3',
                'jalur_daftar' => 'SMBM-TES',
                'nem' => 90.25,
                'kelas_id' => 1,
                'kota' => 'TA00001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Tugas Akhir 2 (Twitter)
            [
                'nim' => '210000004',
                'nama_mhs' => 'Achmadya Ridwan Ilyawan',
                'no_ktp' => '1234567890123404',
                'email' => 'achmadya.ridwan@example.com',
                'telepon' => '081234567804',
                'tgl_lahir' => '2001-05-05',
                'kota_lahir' => 'Yogyakarta',
                'jenis_kelamin' => 1,
                'agama' => 'Islam',
                'gol_darah' => 'AB',
                'anak_ke' => 3,
                'nama_slta' => 'SMA Negeri 4',
                'jalur_daftar' => 'ADIK',
                'nem' => 87.00,
                'kelas_id' => 1,
                'kota' => 'TA00002',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '210000005',
                'nama_mhs' => 'Ari Maulana Hardan',
                'no_ktp' => '1234567890123405',
                'email' => 'ari.maulana@example.com',
                'telepon' => '081234567805',
                'tgl_lahir' => '2000-09-12',
                'kota_lahir' => 'Semarang',
                'jenis_kelamin' => 1,
                'agama' => 'Kristen',
                'gol_darah' => 'A',
                'anak_ke' => 2,
                'nama_slta' => 'SMA Negeri 5',
                'jalur_daftar' => 'SNBT',
                'nem' => 89.50,
                'kelas_id' => 1,
                'kota' => 'TA00002',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '210000006',
                'nama_mhs' => 'Wildan Setya Nugraha',
                'no_ktp' => '1234567890123406',
                'email' => 'wildan.setya@example.com',
                'telepon' => '081234567806',
                'tgl_lahir' => '2001-11-20',
                'kota_lahir' => 'Malang',
                'jenis_kelamin' => 1,
                'agama' => 'Islam',
                'gol_darah' => 'B',
                'anak_ke' => 1,
                'nama_slta' => 'SMA Negeri 6',
                'jalur_daftar' => 'SNBP',
                'nem' => 91.00,
                'kelas_id' => 1,
                'kota' => 'TA00002',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Tugas Akhir 3 (Facebook)
            [
                'nim' => '210000007',
                'nama_mhs' => 'Arief Rahman Ahmadhusein',
                'no_ktp' => '1234567890123407',
                'email' => 'arief.rahman@example.com',
                'telepon' => '081234567807',
                'tgl_lahir' => '2000-02-28',
                'kota_lahir' => 'Denpasar',
                'jenis_kelamin' => 1,
                'agama' => 'Hindu',
                'gol_darah' => 'O',
                'anak_ke' => 2,
                'nama_slta' => 'SMA Negeri 7',
                'jalur_daftar' => 'SMBM-TES',
                'nem' => 86.75,
                'kelas_id' => 1,
                'kota' => 'TA00003',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '210000008',
                'nama_mhs' => 'Hilman Permana',
                'no_ktp' => '1234567890123408',
                'email' => 'hilman.permana@example.com',
                'telepon' => '081234567808',
                'tgl_lahir' => '2001-04-15',
                'kota_lahir' => 'Medan',
                'jenis_kelamin' => 1,
                'agama' => 'Kristen',
                'gol_darah' => 'AB',
                'anak_ke' => 3,
                'nama_slta' => 'SMA Negeri 8',
                'jalur_daftar' => 'ADIK',
                'nem' => 88.25,
                'kelas_id' => 1,
                'kota' => 'TA00003',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '210000009',
                'nama_mhs' => 'Lolla Mariah',
                'no_ktp' => '1234567890123409',
                'email' => 'lolla.mariah@example.com',
                'telepon' => '081234567809',
                'tgl_lahir' => '2000-08-30',
                'kota_lahir' => 'Makassar',
                'jenis_kelamin' => 0,
                'agama' => 'Islam',
                'gol_darah' => 'A',
                'anak_ke' => 1,
                'nama_slta' => 'SMA Negeri 9',
                'jalur_daftar' => 'SNBT',
                'nem' => 90.50,
                'kelas_id' => 1,
                'kota' => 'TA00003',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('mahasiswa')->insert($mahasiswa);

        $membimbing = [
            ['kota' => 'TA00001', 'kode_dosen' => 'DSN003', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00001', 'kode_dosen' => 'DSN006', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'kode_dosen' => 'DSN004', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'kode_dosen' => 'DSN007', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'kode_dosen' => 'DSN005', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'kode_dosen' => 'DSN009', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('membimbing')->insert($membimbing);

        $menguji = [
            ['kota' => 'TA00001', 'kode_dosen' => 'DSN010', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00001', 'kode_dosen' => 'DSN013', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'kode_dosen' => 'DSN011', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'kode_dosen' => 'DSN014', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'kode_dosen' => 'DSN012', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'kode_dosen' => 'DSN014', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('menguji')->insert($menguji);
    }
}
