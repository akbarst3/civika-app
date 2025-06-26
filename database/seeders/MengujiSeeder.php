<?php

namespace Database\Seeders;

use App\Models\Menguji;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MengujiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menguji = [
            ['kota' => 'KoTA121', 'kode_dosen' => 'DSN010', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA121', 'kode_dosen' => 'DSN013', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA122', 'kode_dosen' => 'DSN011', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA122', 'kode_dosen' => 'DSN014', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA123', 'kode_dosen' => 'DSN012', 'penguji_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA123', 'kode_dosen' => 'DSN014', 'penguji_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('menguji')->insert($menguji);
    }
}
