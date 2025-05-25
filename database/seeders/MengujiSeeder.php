<?php

namespace Database\Seeders;

use App\Models\Menguji;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MengujiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menguji::create([
            'kota' => 'KoTA001',
            'kode_dosen' => 'D00002',
            'pembimbing_ke' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Menguji::create([
            'kota' => 'KoTA002',
            'kode_dosen' => 'D00001',
            'pembimbing_ke' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
