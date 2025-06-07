<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembimbingKpPklSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('membimbing_kp_pkl')->insert([
            [
                'id_perusahaan' => 1,
                'tahun' => 2025,
                'kode_dosen' => 'DSN001',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 1,
                'tahun' => 2025,
                'kode_dosen' => 'DSN004',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 2,
                'tahun' => 2025,
                'kode_dosen' => 'DSN002',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 2,
                'tahun' => 2025,
                'kode_dosen' => 'DSN005',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 3,
                'tahun' => 2025,
                'kode_dosen' => 'DSN003',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 3,
                'tahun' => 2025,
                'kode_dosen' => 'DSN006',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 4,
                'tahun' => 2025,
                'kode_dosen' => 'DSN001',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 4,
                'tahun' => 2025,
                'kode_dosen' => 'DSN002',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 5: Supervisors DSN002 (from kp_pkl), DSN003
            [
                'id_perusahaan' => 5,
                'tahun' => 2025,
                'kode_dosen' => 'DSN002',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 5,
                'tahun' => 2025,
                'kode_dosen' => 'DSN003',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 6: Supervisors DSN003 (from kp_pkl), DSN004
            [
                'id_perusahaan' => 6,
                'tahun' => 2025,
                'kode_dosen' => 'DSN003',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 6,
                'tahun' => 2025,
                'kode_dosen' => 'DSN004',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 7: Supervisors DSN004 (from kp_pkl), DSN005
            [
                'id_perusahaan' => 7,
                'tahun' => 2025,
                'kode_dosen' => 'DSN004',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 7,
                'tahun' => 2025,
                'kode_dosen' => 'DSN005',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 8: Supervisors DSN005 (from kp_pkl), DSN006
            [
                'id_perusahaan' => 8,
                'tahun' => 2025,
                'kode_dosen' => 'DSN005',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 8,
                'tahun' => 2025,
                'kode_dosen' => 'DSN006',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 9: Supervisors DSN006 (from kp_pkl), DSN001
            [
                'id_perusahaan' => 9,
                'tahun' => 2025,
                'kode_dosen' => 'DSN006',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 9,
                'tahun' => 2025,
                'kode_dosen' => 'DSN001',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 10: Supervisors DSN001 (from kp_pkl), DSN002
            [
                'id_perusahaan' => 10,
                'tahun' => 2025,
                'kode_dosen' => 'DSN001',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 10,
                'tahun' => 2025,
                'kode_dosen' => 'DSN002',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 11: Supervisors DSN002 (from kp_pkl), DSN003
            [
                'id_perusahaan' => 11,
                'tahun' => 2025,
                'kode_dosen' => 'DSN002',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 11,
                'tahun' => 2025,
                'kode_dosen' => 'DSN003',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // id_perusahaan 12: Supervisors DSN003 (from kp_pkl), DSN004
            [
                'id_perusahaan' => 12,
                'tahun' => 2025,
                'kode_dosen' => 'DSN003',
                'pembimbing_ke' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perusahaan' => 12,
                'tahun' => 2025,
                'kode_dosen' => 'DSN004',
                'pembimbing_ke' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
