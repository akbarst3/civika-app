<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Enum untuk jabatan_dosen
        $jabatanList = ['Kajur', 'Kaprodi'];

        // Kosongkan tabel dulu
        DB::statement('DELETE FROM dosen');

        for ($i = 1; $i <= 10; $i++) {
            DB::table('dosen')->insert([
                'kode_dosen'    => 'D' . str_pad($i, 5, '0', STR_PAD_LEFT), // D00001, D00002 ...
                'nip'           => '19800101200' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_dosen'    => 'Dosen ' . $i,
                'jabatan_dosen' => $jabatanList[array_rand($jabatanList)],
                'ttd'           => null, // bisa diisi path file tanda tangan jika ada
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
