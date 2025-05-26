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
            [
                'nim' => '231511005',
                'nama_mhs' => 'ANNISA SUCI SOLEHA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511006',
                'nama_mhs' => 'ARIQ FAKHRI INDRAWAN',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511008',
                'nama_mhs' => 'DAFNI LANAHTADYA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511009',
                'nama_mhs' => 'DHEA PUTRI ANANDA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511010',
                'nama_mhs' => 'FIRLYANSYAH PUTRA PRATAMA',
                'kelas_id' => '1'
            ],[
                'nim' => '231511011',
                'nama_mhs' => 'GERALDIN GYSRAWA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511012',
                'nama_mhs' => 'HAFIDZ ZAENUL AHKAM',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511013',
                'nama_mhs' => 'HAYA QONITA AMANI',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511014',
                'nama_mhs' => 'IHSAN ERTANSA AZHAR',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511015',
                'nama_mhs' => 'IKHSAN ZUHRI AL GHIFARY',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511016',
                'nama_mhs' => 'ILHAM FAISAL RIDHOTULLOH',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511017',
                'nama_mhs' => 'JAGAD AQSAL NUGROHO SUTARTO',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511018',
                'nama_mhs' => 'MOCH RIZKY TAUFIQURRAHMAN',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511019',
                'nama_mhs' => 'MUHAMAD WAHYU MAULANA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511021',
                'nama_mhs' => 'MUHAMMAD REIVAN NAUFAL MUFID',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511022',
                'nama_mhs' => 'MUHAMMAD RIDHO FIRDAUS',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511023',
                'nama_mhs' => 'NAUFAL ASIDIQ',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511024',
                'nama_mhs' => 'NAUFAL HIDAYATUL FIKRI',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511025',
                'nama_mhs' => 'PRIMA AJI AKBAR',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511026',
                'nama_mhs' => 'RAFKA IMANDA PUTRA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511027',
                'nama_mhs' => 'RASYIID RAAFI SHABAN',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511028',
                'nama_mhs' => 'RIDHO KHAIRIANSYAH AL-RAHMI',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511029',
                'nama_mhs' => 'RIFQI YUDHISTIRA SUMANTRI',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511030',
                'nama_mhs' => 'RINDI INDRIANI',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511031',
                'nama_mhs' => 'TIMOTHY ELROY',
                'kelas_id' => '1'
            ],
            [
                'nim' => '231511032',
                'nama_mhs' => 'YAZID FAUZAN PRASATRIA',
                'kelas_id' => '1'
            ],
            [
                'nim' => '241511001',
                'nama_mhs' => 'OJAN',
                'kelas_id' => '3'
            ],
            [
                'nim' => '241511002',
                'nama_mhs' => 'IYAN',
                'kelas_id' => '3'
            ],
            [
                'nim' => '241511003',
                'nama_mhs' => 'NITO',
                'kelas_id' => '3'
            ],
            [
                'nim' => '231511083',
                'nama_mhs' => 'MUHAMMAD DZAKI NURHIDAYAT',
                'kelas_id' => '2'
            ],
            [
                'nim' => '231511072',
                'nama_mhs' => 'ISYANA PUTRI INDRIANI',
                'kelas_id' => '2'
            ],
            [
                'nim' => '231511077',
                'nama_mhs' => 'FAUZAN RIZKY RAMADHAN',
                'kelas_id' => '2'
            ],
        ];

        Mahasiswa::insert($mahasiswas);
    }
}