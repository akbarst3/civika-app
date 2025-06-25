<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                //'id_user', 'nim', 'kode_dosen', 'email', 'password',
                'id_user' => 1,
                'nim' => null,
                'kode_dosen' => null,
                'email' => 'muhammadgumilang19@gmail.com',
                'password' => Hash::make('123456'), // Hash the password
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'nim' => '211511003',
                'kode_dosen' => null,
                'email' => 'www@gmail.com',
                'password' => Hash::make('123456'), // Hash the password
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
