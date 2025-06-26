<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            ProdiSeeder::class,
            KelasSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
//            TugasAkhirSeeder::class,
//            MembimbingTASeeder::class,
//            MengujiTASeeder::class,
//            KpPklSeeder::class,
//            MembimbingKpPklSeeder::class,
//            MengujiKpPklSeeder::class,
            UserSeeder::class,
        ]);
    }
}
