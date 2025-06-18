<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        Mahasiswa::create([
            'nim' => 'M0000001',
            'nama_mhs' => 'Budi Santoso',
            'no_ktp' => $faker->nik(),
            'email' => 'budi@example.com',
            'telepon' => $faker->phoneNumber,
            'tgl_lahir' => '2000-05-15',
            'kota_lahir' => 'Jakarta',
            'jenis_kelamin' => 1,
            'agama' => 'Islam',
            'gol_darah' => 'A',
            'anak_ke' => 1,
            'nama_slta' => 'SMA Negeri 1',
            'jalur_daftar' => 'SNBT',
            'nem' => 85.50,
            'kelas_id' => 1, // Asumsi kelas ada
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Mahasiswa::create([
            'nim' => 'M0000002',
            'nama_mhs' => 'Ani Wijaya',
            'no_ktp' => $faker->nik(),
            'email' => 'ani@example.com',
            'telepon' => $faker->phoneNumber,
            'tgl_lahir' => '2001-08-20',
            'kota_lahir' => 'Bandung',
            'jenis_kelamin' => 0,
            'agama' => 'Kristen',
            'gol_darah' => 'B',
            'anak_ke' => 2,
            'nama_slta' => 'SMA Negeri 2',
            'jalur_daftar' => 'SNBP',
            'nem' => 90.25,
            'kelas_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
