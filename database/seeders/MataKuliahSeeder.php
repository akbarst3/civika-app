<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mata_kuliahs = [
            [
                'kode_matkul' => '21IF2012',
                'nama_matkul' => 'Basis Data',
                'jumlah_sks' => '4'
            ],
            [
                'kode_matkul' => '21IF2013',
                'nama_matkul' => 'Pengantar Rekayasa Perangkat Lunak',
                'jumlah_sks' => '4'
            ],
            [
                'kode_matkul' => '21IF2015',
                'nama_matkul' => 'Komputer Grafik',
                'jumlah_sks' => '3'
            ],
            [
                'kode_matkul' => '21IF2011',
                'nama_matkul' => 'Pemrograman Berorientasi Objek',
                'jumlah_sks' => '3'
            ],
            [
                'kode_matkul' => '21IF2010',
                'nama_matkul' => 'Matematika Diskrit 2',
                'jumlah_sks' => '3'
            ],
            [
                'kode_matkul' => '21IF2016',
                'nama_matkul' => 'Proyek 3 : Pengembangan Perangkat Lunak Berbasis Web',
                'jumlah_sks' => '3'
            ],
            [
                'kode_matkul' => '21IF2014',
                'nama_matkul' => 'Aljabar Linear',
                'jumlah_sks' => '2'
            ],
        ];

        MataKuliah::insert($mata_kuliahs);
    }
}