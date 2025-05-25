<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run()
    {
        DB::table('absensi')->insert([
            [
                'nim'               => '230101001',
                'semester'          => 1,
                'jml_sakit'         => 2,
                'jml_izin'          => 1,
                'jml_alfa'          => 0,
                'nilai_penghayatan' => 90,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ]);
    }
}
