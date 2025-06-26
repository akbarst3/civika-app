<?php

namespace Database\Seeders;

use App\Models\Membimbing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $membimbing = [
            ['kota' => 'KoTA121', 'kode_dosen' => 'DSN003', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA121', 'kode_dosen' => 'DSN006', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA122', 'kode_dosen' => 'DSN004', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA122', 'kode_dosen' => 'DSN007', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA123', 'kode_dosen' => 'DSN005', 'pembimbing_ke' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kota' => 'KoTA123', 'kode_dosen' => 'DSN009', 'pembimbing_ke' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('membimbing')->insert($membimbing);
    }
}
