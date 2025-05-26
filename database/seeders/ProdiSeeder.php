<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prodi')->insert([
            ['kode_prodi' => 1, 'nama_prodi' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['kode_prodi' => 2, 'nama_prodi' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
