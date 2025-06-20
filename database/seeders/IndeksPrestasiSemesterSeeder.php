<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class IndeksPrestasiSemesterSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = [
            ['nim' => '231511020'], ['nim' => '231511001'], ['nim' => '231511002'], ['nim' => '231511003'],
            ['nim' => '231511004'], ['nim' => '231511005'], ['nim' => '231511006'], ['nim' => '231511008'],
            ['nim' => '231511009'], ['nim' => '231511010'], ['nim' => '231511011'], ['nim' => '231511012'],
            ['nim' => '231511013'], ['nim' => '231511014'], ['nim' => '231511015'], ['nim' => '231511016'],
            ['nim' => '231511017'], ['nim' => '231511018'], ['nim' => '231511019'], ['nim' => '231511021'],
            ['nim' => '231511022'], ['nim' => '231511023'], ['nim' => '231511024'], ['nim' => '231511025'],
            ['nim' => '231511026'], ['nim' => '231511027'], ['nim' => '231511028'], ['nim' => '231511029'],
            ['nim' => '231511030'], ['nim' => '231511031'], ['nim' => '231511032'], ['nim' => '241511001'],
            ['nim' => '241511002'], ['nim' => '241511003'], ['nim' => '231511083'], ['nim' => '231511072'],
            ['nim' => '231511077'], ['nim' => '231511078'], ['nim' => '231511079'], ['nim' => '231511080'], 
            ['nim' => '231511081'], ['nim' => '231511082'], ['nim' => '231511084'], ['nim' => '201511083'],
            ['nim' => '231511085'], ['nim' => '231511086'], ['nim' => '231511087'], ['nim' => '231511088'], 
            ['nim' => '231511089'], ['nim' => '231511090'], ['nim' => '231511091'], ['nim' => '231511092'], 
            ['nim' => '231511093'], ['nim' => '231511094'], ['nim' => '231511095'], ['nim' => '231511096'], 
            ['nim' => '231511097'], ['nim' => '231511098'], ['nim' => '231511099'], ['nim' => '231511100'], 
            ['nim' => '231511101'], ['nim' => '231511102'], ['nim' => '231511103'],
        ];

        $data = [];
        foreach ($mahasiswa as $mhs) {
            for ($semester = 1; $semester <= 5; $semester++) {
                $ip = number_format(mt_rand(200, 400) / 100, 2);
                $bobot = mt_rand(45, 60);
                $jumlah_d = mt_rand(0, 2);
                $status = $ip >= 2.75 ? 'Lulus' : 'Perlu Evaluasi';
                $keterangan = $ip >= 3.5 ? 'Sangat Baik' : ($ip >= 2.75 ? 'Baik' : 'Perlu Perbaikan');

                $data[] = [
                    'nim' => $mhs['nim'],
                    'semester' => (string) $semester,
                    'status' => $status,
                    'indeks_prestasi' => $ip,
                    'nilai_bobot' => $bobot,
                    'jumlah_d' => $jumlah_d,
                    'keterangan' => $keterangan,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        DB::table('indeks_prestasi_semester')->insert($data);
    }
}
