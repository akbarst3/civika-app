<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen')->insert([
            [
                'kode_dosen' => 'DSN003',
                'nip' => '1987654321098767',
                'nidn' => '0002039008',
                'nama_dosen' => 'Rahil Jumiyani, S.ST., M.Sc.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN004',
                'nip' => '1987654321098768',
                'nidn' => '0006017202',
                'nama_dosen' => 'Bambang Wisnuadhi, S.Si., M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN005',
                'nip' => '1987654321098769',
                'nidn' => '0410028502',
                'nama_dosen' => 'Ade Hodijah, S.T., M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN006',
                'nip' => '1987654321098770',
                'nidn' => '0026126001',
                'nama_dosen' => 'Didik Suwito Pribadi, BSCS., M.Kom.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN007',
                'nip' => '1987654321098771',
                'nidn' => '0028129303',
                'nama_dosen' => 'Djoko Cahyo Utomo Lieharyani, S.Kom., M.MT.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN009',
                'nip' => '1987654321098773',
                'nidn' => '0028096001',
                'nama_dosen' => 'Urip Teguh Setijohatmo, BSCS., M.Kom.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN010',
                'nip' => '1987654321098774',
                'nidn' => '0013126303',
                'nama_dosen' => 'Dr. Nurjannah Syakrani, DRA., M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN011',
                'nip' => '1987654321098775',
                'nidn' => '0009116105',
                'nama_dosen' => 'Dr. Transmissia Semiawan, BSCS., M.IT.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN012',
                'nip' => '1987654321098776',
                'nidn' => '0017058704',
                'nama_dosen' => 'Akhmad Bakhrun, S.Kom, M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN013',
                'nip' => '1987654321098777',
                'nidn' => '0015086205',
                'nama_dosen' => 'Ir. Irawan Thamrin, M.T.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_dosen' => 'DSN014',
                'nip' => '1987654321098778',
                'nidn' => '0014016104',
                'nama_dosen' => 'Drs. Eddy Bambang Soewono, M.Kom.',
                'jabatan_dosen' => null,
                'ttd' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
//        // Enum untuk jabatan_dosen
//        $jabatanList = ['Kajur', 'Kaprodi'];
//
//        // Kosongkan tabel dulu
//        DB::statement('DELETE FROM dosen');
//
//        for ($i = 1; $i <= 10; $i++) {
//            DB::table('dosen')->insert([
//                'kode_dosen'    => 'D' . str_pad($i, 5, '0', STR_PAD_LEFT), // D00001, D00002 ...
//                'nip'           => '19800101200' . str_pad($i, 3, '0', STR_PAD_LEFT),
//                'nama_dosen'    => 'Dosen ' . $i,
//                'jabatan_dosen' => $jabatanList[array_rand($jabatanList)],
//                'ttd'           => null, // bisa diisi path file tanda tangan jika ada
//                'created_at'    => now(),
//                'updated_at'    => now(),
//            ]);
//        }
    }
}
