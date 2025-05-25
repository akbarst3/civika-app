<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jalurList = ['SNMPTN', 'SBMPTN', 'Mandiri', 'Lainnya'];
        $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $golDarah = ['A', 'B', 'AB', 'O'];
        $kelasId = DB::table('kelas')->pluck('id')->first(); // ambil 1 id dari tabel kelas

        for ($i = 1; $i <= 10; $i++) {
            DB::table('mahasiswa')->insert([
                'nim' => '2301010' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nama_kelas'    => chr(64 + (($i % 3) + 1)), // A, B, C
                'angkatan'      => '2023',
                'nama_mhs'      => 'Mahasiswa ' . $i,
                'no_ktp'        => '32730101010100' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'email'         => 'mhs' . $i . '@example.com',
                'telepon'       => '08' . rand(1000000000, 9999999999),
                'tgl_lahir'     => Carbon::parse('2005-01-01')->addDays(rand(0, 365)),
                'kota_lahir'    => fake()->city(),
                'jenis_kelamin' => rand(0, 1),
                'agama'         => $agamaList[array_rand($agamaList)],
                'gol_darah'     => $golDarah[array_rand($golDarah)],
                'anak_ke'       => rand(1, 5),
                'nama_slta'     => 'SMAN ' . rand(1, 100),
                'jalur_daftar'  => $jalurList[array_rand($jalurList)],
                'nem'           => number_format(rand(750, 1000) / 100, 2),
                'kelas_id'      => $kelasId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
