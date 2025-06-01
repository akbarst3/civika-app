<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasAkhirSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed tabel dosen (4 dosen, dengan nidn wajib)
        $dosen = [
            ['kode_dosen' => 'D00001', 'nip' => '1234567890123456', 'nama_dosen' => 'Dr. Ahmad Yani', 'nidn' => '0012345601', 'jabatan_dosen' => 'Kajur', 'ttd' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kode_dosen' => 'D00002', 'nip' => '1234567890123457', 'nama_dosen' => 'Prof. Budi Santoso', 'nidn' => '0012345602', 'jabatan_dosen' => 'Kaprodi', 'ttd' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kode_dosen' => 'D00003', 'nip' => '1234567890123458', 'nama_dosen' => 'Dr. Citra Dewi', 'nidn' => '0012345603', 'jabatan_dosen' => null, 'ttd' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kode_dosen' => 'D00004', 'nip' => '1234567890123459', 'nama_dosen' => 'Dr. Eko Prasetyo', 'nidn' => '0012345604', 'jabatan_dosen' => null, 'ttd' => null, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('dosen')->insert($dosen);

        // 2. Seed tabel tugas_akhir (3 tugas akhir)
        $tugas_akhir = [
            ['kota' => 'TA00001', 'topik' => 'Analisis Machine Learning untuk Prediksi Cuaca', 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'topik' => 'Sistem Informasi Berbasis Web untuk E-Commerce', 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'topik' => 'Optimasi Jaringan 5G menggunakan AI', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('tugas_akhir')->insert($tugas_akhir);

        // 3. Seed tabel mahasiswa (9 mahasiswa, 3 per tugas akhir)
        $mahasiswa = [
            // Tugas Akhir 1
            [
                'nim' => '123456001',
                'nama_mhs' => 'Andi Pratama',
                'no_ktp' => '1234567890123451',
                'email' => 'andi.pratama@example.com',
                'telepon' => '081234567890',
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
                'nim' => '123456002',
                'nama_mhs' => 'Bunga Sari',
                'no_ktp' => '1234567890123452',
                'email' => 'bunga.sari@example.com',
                'telepon' => '081234567891',
                'tgl_lahir' => '2001-03-22',
                'kota_lahir' => 'Bandung',
                'jenis_kelamin' => 0,
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
                'nim' => '123456003',
                'nama_mhs' => 'Candra Wijaya',
                'no_ktp' => '1234567890123453',
                'email' => 'candra.wijaya@example.com',
                'telepon' => '081234567892',
                'tgl_lahir' => '2000-07-10',
                'kota_lahir' => 'Surabaya',
                'jenis_kelamin' => 1,
                'agama' => 'Hindu',
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
            // Tugas Akhir 2
            [


                'nim' => '123456004',
                'nama_mhs' => 'Dewi Lestari',
                'no_ktp' => '1234567890123454',
                'email' => 'dewi.lestari@example.com',
                'telepon' => '081234567893',
                'tgl_lahir' => '2001-05-05',
                'kota_lahir' => 'Yogyakarta',
                'jenis_kelamin' => 0,
                'agama' => 'Buddha',
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
                'nim' => '123456005',
                'nama_mhs' => 'Eko Nugroho',
                'no_ktp' => '1234567890123455',
                'email' => 'eko.nugroho@example.com',
                'telepon' => '081234567894',
                'tgl_lahir' => '2000-09-12',
                'kota_lahir' => 'Semarang',
                'jenis_kelamin' => 1,
                'agama' => 'Islam',
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
                'nim' => '123456006',
                'nama_mhs' => 'Fitri Rahayu',
                'no_ktp' => '1234567890123456',
                'email' => 'fitri.rahayu@example.com',
                'telepon' => '081234567895',
                'tgl_lahir' => '2001-11-20',
                'kota_lahir' => 'Malang',
                'jenis_kelamin' => 0,
                'agama' => 'Katholik',
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
            // Tugas Akhir 3
            [
                'nim' => '123456007',
                'nama_mhs' => 'Gita Permata',
                'no_ktp' => '1234567890123457',
                'email' => 'gita.permata@example.com',
                'telepon' => '081234567896',
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
                'nim' => '123456008',
                'nama_mhs' => 'Hadi Susanto',
                'no_ktp' => '1234567890123458',
                'email' => 'hadi.susanto@example.com',
                'telepon' => '081234567897',
                'tgl_lahir' => '2001-04-15',
                'kota_lahir' => 'Medan',
                'jenis_kelamin' => 0,
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
                'nim' => '123456009',
                'nama_mhs' => 'Indra Kurniawan',
                'no_ktp' => '1234567890123459',
                'email' => 'indra.kurniawan@example.com',
                'telepon' => '081234567898',
                'tgl_lahir' => '2000-08-30',
                'kota_lahir' => 'Makassar',
                'jenis_kelamin' => 1,
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

        // 4. Seed tabel membimbing (2 dosen pembimbing per tugas akhir)
        $membimbing = [
            // Tugas Akhir 1
            ['kota' => 'TA00001', 'kode_dosen' => 'D00001', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00001', 'kode_dosen' => 'D00002', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            // Tugas Akhir 2
            ['kota' => 'TA00002', 'kode_dosen' => 'D00002', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'kode_dosen' => 'D00003', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            // Tugas Akhir 3
            ['kota' => 'TA00003', 'kode_dosen' => 'D00003', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'kode_dosen' => 'D00004', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('membimbing')->insert($membimbing);

        // 5. Seed tabel menguji (2 dosen penguji per tugas akhir)
        $menguji = [
            // Tugas Akhir 1
            ['kota' => 'TA00001', 'kode_dosen' => 'D00003', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00001', 'kode_dosen' => 'D00004', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            // Tugas Akhir 2
            ['kota' => 'TA00002', 'kode_dosen' => 'D00001', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00002', 'kode_dosen' => 'D00004', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            // Tugas Akhir 3
            ['kota' => 'TA00003', 'kode_dosen' => 'D00001', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'TA00003', 'kode_dosen' => 'D00002', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('menguji')->insert($menguji);
    }
}
