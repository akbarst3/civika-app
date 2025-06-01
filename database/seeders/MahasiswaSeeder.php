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
                'kelas_id' => 5, // Pastikan kelas dengan ID 1 sudah ada di tabel `kelas`
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
