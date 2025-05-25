<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasSeeder extends Seeder
{
    public function run()
    {
        DB::table('kelas')->insert([
            [
                'id'          => 1,
                'nama_kelas'  => 'C',
                'angkatan'    => 2022,
                'kode_prodi'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);
    }
}
