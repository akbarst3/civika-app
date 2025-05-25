<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            [
                'kode_dosen' => 'KO001N',
                'nama_dosen' => 'Ade Chandra Nugraha, S.Si.,MT'
            ],
            [
                'kode_dosen' => 'KO009N',
                'nama_dosen' => 'Santi Sundari, S.Si., MT'
            ],
            [
                'kode_dosen' => 'KO013N',
                'nama_dosen' => 'Yudi Widhiyasana, S.Si., MT'
            ],
            [
                'kode_dosen' => 'KO061N',
                'nama_dosen' => 'Zulkifli Arsyad, S.Kom., M.T'
            ],
            [
                'kode_dosen' => 'KO071N',
                'nama_dosen' => 'Hashri Hayati, S.T., M.T'
            ],
            [
                'kode_dosen' => 'KO072N',
                'nama_dosen' => 'Lukmannul Hakim Firdaus, S.Kom., M.T'
            ],
            [
                'kode_dosen' => 'KO074N',
                'nama_dosen' => 'Muhammad Rizqi Sholahuddin, S.Si., M.T'
            ],
        ];

        Dosen::insert($dosens);
    }
}
