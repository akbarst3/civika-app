<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nim' => '210000001', 'nama_mhs' => 'Aldrin Rayhan Putra'],
            ['nim' => '210000002', 'nama_mhs' => 'Ananta Destawardhana'],
            ['nim' => '210000003', 'nama_mhs' => 'M. Fatur Maulidan Azzahra'],
            ['nim' => '210000004', 'nama_mhs' => 'Achmadya Ridwan Ilyawan'],
            ['nim' => '210000005', 'nama_mhs' => 'Ari Maulana Hardan'],
            ['nim' => '210000006', 'nama_mhs' => 'Wildan Setya Nugraha'],
            ['nim' => '210000007', 'nama_mhs' => 'Arief Rahman Ahmadhusein'],
            ['nim' => '210000008', 'nama_mhs' => 'Hilman Permana'],
            ['nim' => '210000009', 'nama_mhs' => 'Lolla Mariah'],
            ['nim' => '210000010', 'nama_mhs' => 'Lolla Mariah'],
            ['nim' => '210000011', 'nama_mhs' => 'Lolla Mariah'],
            ['nim' => '210000012', 'nama_mhs' => 'Lolla Mariah'],
        ];

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
                'angkatan' => '2021',
                'nama_slta' => 'SMAN 1 Bandung',
                'jalur_daftar' => 'SNBT',
                'kelas_id' => 5, // Pastikan kelas dengan ID 1 sudah ada di tabel `kelas`
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
