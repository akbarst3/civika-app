<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            [
                'kode_prodi' => '1',
                'nama_prodi' => 'D3'
            ],
            [
                'kode_prodi' => '2',
                'nama_prodi' => 'D4'
            ]
        ];

        Prodi::insert($prodis);
    }
}
