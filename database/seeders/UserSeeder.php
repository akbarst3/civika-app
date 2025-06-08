<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Mahasiswa
        User::create([
            'id_user' => 1,
            'nim' => '230101001',
            'kode_dosen' => null,
            'email' => 'mahasiswa@example.com',
            'password' => 'password123', 
        ]);

        // Dosen
        User::create([
            'id_user' => 2,
            'nim' => null,
            'kode_dosen' => 'D00001',
            'email' => 'dosen@example.com',
            'password' => 'password123',
        ]);

        // Tata Usaha
        User::create([
            'id_user' => 4,
            'nim' => null,
            'kode_dosen' => null,
            'email' => 'tu@example.com',
            'password' => 'password123',
        ]);
    }
}
