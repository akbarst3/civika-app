<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = ['A', 'B', 'C'];
        $angkatan = ['2023', '2022', '2021'];
        $prodi_ids = DB::table('prodi')->pluck('kode_prodi')->toArray();

        $data = [];
        foreach ($kelas as $k) {
            foreach ($angkatan as $a) {
                foreach ($prodi_ids as $p) {
                    $data[] = [
                        'nama_kelas' => $k,
                        'angkatan' => $a,
                        'kode_prodi' => $p,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        DB::table('kelas')->insert($data);
    }
}
