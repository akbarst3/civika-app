<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dosen::create(['kode_dosen' => 'D00001', 'nama_dosen' => 'Dr. Ahmad', 'created_at' => now(), 'updated_at' => now()]);
        Dosen::create(['kode_dosen' => 'D00002', 'nama_dosen' => 'Prof. Siti', 'created_at' => now(), 'updated_at' => now()]);
    }
}
