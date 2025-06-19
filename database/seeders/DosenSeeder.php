<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen')->insert([
            [
                'kode_dosen' => 'DSN001',
                'nip' => '1987654321098767',
                'nidn' => '1112345678',
                'nama_dosen' => 'Rahil Jumiyani, S.ST., M.Sc.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN002',
                'nip' => '1987654321098768',
                'nidn' => '1112345679',
                'nama_dosen' => 'Bambang Wisnuadhi, S.Si., M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN003',
                'nip' => '1987654321098769',
                'nidn' => '1112345680',
                'nama_dosen' => 'Ade Hodijah, S.T., M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN004',
                'nip' => '1987654321098770',
                'nidn' => '1112345681',
                'nama_dosen' => 'Didik Suwito Pribadi, BSCS., M.Kom.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN005',
                'nip' => '1987654321098771',
                'nidn' => '1112345682',
                'nama_dosen' => 'Djoko Cahyo Utomo Lieharyani, S.Kom., M.MT.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN006',
                'nip' => '1987654321098773',
                'nidn' => '1112345683',
                'nama_dosen' => 'Urip Teguh Setijohatmo, BSCS., M.Kom.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN010',
                'nip' => '1987654321098774',
                'nidn' => '1112345684',
                'nama_dosen' => 'Dr. Nurjannah Syakrani, DRA., M.T.',
                'jabatan_dosen' => 'Sekretaris 2',
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN011',
                'nip' => '1987654321098775',
                'nidn' => '1112345685',
                'nama_dosen' => 'Dr. Transmissia Semiawan, BSCS., M.IT.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN012',
                'nip' => '1987654321098776',
                'nidn' => '1112345686',
                'nama_dosen' => 'Akhmad Bakhrun, S.Kom, M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN013',
                'nip' => '1987654321098777',
                'nidn' => '1112345687',
                'nama_dosen' => 'Ir. Irawan Thamrin, M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN014',
                'nip' => '1987654321098778',
                'nidn' => '1112345688',
                'nama_dosen' => 'Drs. Eddy Bambang Soewono, M.Kom.',
                'jabatan_dosen' => 'Kajur',
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN015',
                'nip' => '1987654321098779',
                'nidn' => '1112345689',
                'nama_dosen' => 'Drs. Eddy Bambang Soewono, M.Kom.',
                'jabatan_dosen' => 'Kaprodi',
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
