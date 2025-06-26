<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tugas_akhir = [
            [
                'kota' => 'KoTA121',
                'topik' => 'Pengembangan Aplikasi Mobile Berbasis AI untuk Manajemen Data',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kota' => 'KoTA122',
                'topik' => 'Optimalisasi Jaringan Internet of Things (IoT) di Lingkungan Perkotaan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kota' => 'KoTA123',
                'topik' => 'Sistem Rekomendasi Berbasis Machine Learning untuk E-Commerce',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

//        DB::table('tugas_akhir')->insert($tugas_akhir);


        $data = [
            ['nim' => '211511003', 'nama_mhs' => 'Aldrin Rayhan Putra'],
            ['nim' => '211511004', 'nama_mhs' => 'Ananta Destawardhana'],
            ['nim' => '211511020', 'nama_mhs' => 'M. Fatur Maulidan Azzahra'],
            ['nim' => '211511001', 'nama_mhs' => 'Achmadya Ridwan Ilyawan'],
            ['nim' => '211511007', 'nama_mhs' => 'Ari Maulana Hardan'],
            ['nim' => '211511032', 'nama_mhs' => 'Wildan Setya Nugraha'],
            ['nim' => '211511009', 'nama_mhs' => 'Arief Rahman Ahmadhusein'],
            ['nim' => '211511015', 'nama_mhs' => 'Hilman Permana'],
            ['nim' => '211511018', 'nama_mhs' => 'Lolla Mariah'],
        ];
        $jalurList = ['SNMPTN', 'SBMPTN', 'Mandiri', 'Lainnya'];
        $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $golDarah = ['A', 'B', 'AB', 'O'];
        $kelasId = DB::table('kelas')->pluck('id')->first(); // ambil 1 id dari tabel kelas

        foreach ($data as $mhs) {
            DB::table('mahasiswa')->insert([
                'nim' => $mhs['nim'],
                'nama_mhs' => $mhs['nama_mhs'],
                'no_ktp' => Str::random(16),
                'email' => Str::slug($mhs['nama_mhs'], '.') . '@example.com',
                'telepon' => '0812' . rand(10000000, 99999999),
                'tgl_lahir' => '2003-01-01',
                'kota_lahir' => 'Bandung',
                'jenis_kelamin' => true,
                'agama' => 'Islam',
                'gol_darah' => 'O',
                'anak_ke' => 1,
                'nama_slta' => 'SMAN 1 Bandung',
                'jalur_daftar' => 'SNBT',
                'kelas_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $mahasiswa = [
            // Tugas Akhir 1 (KoTA121)
            [
                'nim' => '221511003',
                'nama_mhs' => 'Rina Sari',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '221511004',
                'nama_mhs' => 'Budi Santoso',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '221511020',
                'nama_mhs' => 'Anita Wijaya',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Tugas Akhir 2 (KoTA122)
            [
                'nim' => '221511001',
                'nama_mhs' => 'Dedi Pratama',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '221511007',
                'nama_mhs' => 'Lestari Putri',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '221511032',
                'nama_mhs' => 'Ardi Nugroho',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Tugas Akhir 3 (KoTA123)
            [
                'nim' => '221511009',
                'nama_mhs' => 'Siti Rahayu',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '221511015',
                'nama_mhs' => 'Joko Susilo',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nim' => '221511018',
                'nama_mhs' => 'Maya Hartono',
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
                'kelas_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('mahasiswa')->insert($mahasiswa);
    }
}
