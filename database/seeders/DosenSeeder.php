<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
                'nama_dosen' => 'Hashri Hayati, S.T., M.T',
                'jabatan_dosen' => 'Kaprodi-D3'
            ],
            [
                'kode_dosen' => 'KO072N',
                'nama_dosen' => 'Lukmannul Hakim Firdaus, S.Kom., M.T',
                'jabatan_dosen' => 'Kaprodi-D4'
            ],
            [
                'kode_dosen' => 'KO074N',
                'nama_dosen' => 'Muhammad Rizqi Sholahuddin, S.Si., M.T'
            ],
        ];

        Dosen::insert($dosens);
    }
}

    //     // Enum untuk jabatan_dosen
    //     $jabatanList = ['Kajur', 'Kaprodi'];

    //     // Kosongkan tabel dulu
    //     DB::statement('DELETE FROM dosen');

    //     for ($i = 1; $i <= 10; $i++) {
    //         DB::table('dosen')->insert([
    //             'kode_dosen'    => 'D' . str_pad($i, 5, '0', STR_PAD_LEFT), // D00001, D00002 ...
    //             'nip'           => '19800101200' . str_pad($i, 3, '0', STR_PAD_LEFT),
    //             'nama_dosen'    => 'Dosen ' . $i,
    //             'jabatan_dosen' => $jabatanList[array_rand($jabatanList)],
    //             'ttd'           => null, // bisa diisi path file tanda tangan jika ada
    //             'created_at'    => now(),
    //             'updated_at'    => now(),
    //         ]);
    //     }
    // }
