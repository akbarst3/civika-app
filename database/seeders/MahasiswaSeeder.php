<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswas = [
            [
                'nim' => '231511020',
                'nama_mhs' => 'MUHAMMAD HARISH AL-RASYIDI',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511001',
                'nama_mhs' => 'AISYAH NAOMI KAZZAYARA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511002',
                'nama_mhs' => 'ALYA NISRINA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511003',
                'nama_mhs' => 'ANGELITA TAPITTA HUTAHAEAN',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511004',
                'nama_mhs' => 'ANNISA DIAN FADILLAH',
                'kelas_id' => '1'
            ],
        ];

        Mahasiswa::insert($mahasiswas);
    }
}
