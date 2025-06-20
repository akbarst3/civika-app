<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
            ['nim' => '231511078', 'nama_mhs' => 'Farhan Aditya Ramadhan', 'kelas_id' => 3],
            ['nim' => '231511079', 'nama_mhs' => 'Nadya Rizkiana Putri',     'kelas_id' => 3],
            
            ['nim' => '231511080', 'nama_mhs' => 'Hafiz Albarqi Maulana',    'kelas_id' => 4],
            ['nim' => '231511081', 'nama_mhs' => 'Salsabila Anindya Dewi',   'kelas_id' => 4],
            
            ['nim' => '231511082', 'nama_mhs' => 'Dimas Fajar Saputra',      'kelas_id' => 5],
            ['nim' => '201511083', 'nama_mhs' => 'Zahra Nabila Rahmah',      'kelas_id' => 5],
            
            ['nim' => '231511084', 'nama_mhs' => 'Ilham Ramzi Firmansyah',   'kelas_id' => 6],
            ['nim' => '231511085', 'nama_mhs' => 'Aulia Nurfadillah',        'kelas_id' => 6],
            
            ['nim' => '231511086', 'nama_mhs' => 'Fauzan Hilmi Wicaksono',   'kelas_id' => 7],
            ['nim' => '231511087', 'nama_mhs' => 'Indira Sekar Ayu',         'kelas_id' => 7],
            
            ['nim' => '231511088', 'nama_mhs' => 'Yusuf Alfarizi Pratama',   'kelas_id' => 8],
            ['nim' => '231511089', 'nama_mhs' => 'Putri Larasati Anjani',    'kelas_id' => 8],
            
            ['nim' => '231511090', 'nama_mhs' => 'Arsyad Maulana Yusuf',     'kelas_id' => 9],
            ['nim' => '231511091', 'nama_mhs' => 'Keysha Afifah Salsabila',  'kelas_id' => 9],
            
            ['nim' => '231511092', 'nama_mhs' => 'Bima Aditya Ramadhan',     'kelas_id' => 10],
            ['nim' => '231511093', 'nama_mhs' => 'Syifa Nur Halimah',        'kelas_id' => 10],
            
            ['nim' => '231511094', 'nama_mhs' => 'Reza Alamsyah Ridwan',     'kelas_id' => 11],
            ['nim' => '231511095', 'nama_mhs' => 'Maura Felisha Ayuni',      'kelas_id' => 11],
            
            ['nim' => '231511096', 'nama_mhs' => 'Iqbal Fauzi Ramadhan',     'kelas_id' => 12],
            ['nim' => '231511097', 'nama_mhs' => 'Tiara Lestari Anggraini',  'kelas_id' => 12],
            
            ['nim' => '231511098', 'nama_mhs' => 'Rafi Haidar Nugraha',      'kelas_id' => 13],
            ['nim' => '231511099', 'nama_mhs' => 'Vania Dwi Aprillia',       'kelas_id' => 13],
            
            ['nim' => '231511100', 'nama_mhs' => 'Fathan Al Ghazali',        'kelas_id' => 14],
            ['nim' => '231511101', 'nama_mhs' => 'Mutiara Khairunnisa',      'kelas_id' => 14],
            
            ['nim' => '231511102', 'nama_mhs' => 'Galang Prasetya Adi',      'kelas_id' => 15],
            ['nim' => '231511103', 'nama_mhs' => 'Dinda Safira Zahra',       'kelas_id' => 15],
        ];

        Mahasiswa::insert($mahasiswas);

        //hasil nerge branch autentikasi
        // $jalurList = ['SNMPTN', 'SBMPTN', 'Mandiri', 'Lainnya'];
        // $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        // $golDarah = ['A', 'B', 'AB', 'O'];
        // $kelasId = DB::table('kelas')->pluck('id')->first(); // ambil 1 id dari tabel kelas

        // for ($i = 1; $i <= 10; $i++) {
        //     DB::table('mahasiswa')->insert([
        //         'nim' => '2301010' . str_pad($i, 2, '0', STR_PAD_LEFT),
        //         'nama_kelas'    => chr(64 + (($i % 3) + 1)), // A, B, C
        //         'angkatan'      => '2023',
        //         'nama_mhs'      => 'Mahasiswa ' . $i,
        //         'no_ktp'        => '32730101010100' . str_pad($i, 2, '0', STR_PAD_LEFT),
        //         'email'         => 'mhs' . $i . '@example.com',
        //         'telepon'       => '08' . rand(1000000000, 9999999999),
        //         'tgl_lahir'     => Carbon::parse('2005-01-01')->addDays(rand(0, 365)),
        //         'kota_lahir'    => fake()->city(),
        //         'jenis_kelamin' => rand(0, 1),
        //         'agama'         => $agamaList[array_rand($agamaList)],
        //         'gol_darah'     => $golDarah[array_rand($golDarah)],
        //         'anak_ke'       => rand(1, 5),
        //         'nama_slta'     => 'SMAN ' . rand(1, 100),
        //         'jalur_daftar'  => $jalurList[array_rand($jalurList)],
        //         'nem'           => number_format(rand(750, 1000) / 100, 2),
        //         'kelas_id'      => $kelasId,
        //         'created_at'    => now(),
        //         'updated_at'    => now(),
        //     ]);
        // }
    }
}
