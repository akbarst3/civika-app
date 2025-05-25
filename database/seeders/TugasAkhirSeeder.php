<?php

namespace Database\Seeders;

use App\Models\TugasAkhir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TugasAkhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TugasAkhir::create([
            'kota' => 'KoTA001',
            'nim' => 'M0000001',
            'topik' => 'Analisis Sistem Informasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        TugasAkhir::create([
            'kota' => 'KoTA002',
            'nim' => 'M0000002',
            'topik' => 'Machine Learning untuk Prediksi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
