<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        DB::table('prodi')->insert([
            [
                'kode_prodi'  => '1',
                'nama_prodi'  => 'D3',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],

            // Tambahkan data lain jika perlu
        ]);
    }
}
