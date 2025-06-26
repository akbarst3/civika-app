<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Ayah;
use App\Models\Ibu;
use App\Models\DataTinggal;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class DataMahasiswaImport implements ToModel, WithHeadingRow, WithMultipleSheets, SkipsUnknownSheets
{
    protected string $angkatan;
    protected ?string $namaKelas;
    protected ?string $namaProdi;
    protected ?int $kelasId;
    protected array $validSheets = [];

    public function __construct($angkatan, $sheetName = null)
    {
        $this->angkatan = $angkatan;
        if ($sheetName) {
            $parts = explode('-', $sheetName);
            $classPart = $parts[0] ?? null;
            $this->namaProdi = $parts[1] ?? null;
            $this->namaKelas = $classPart ? preg_replace('/\d+/', '', $classPart) : null;

            if (!$this->namaKelas || !$this->namaProdi || count($parts) !== 2) {
                throw new Exception("Format nama sheet '{$sheetName}' salah. Harus berupa 'Kelas-Prodi', contoh: '1A-D3'.");
            }

            $prodi = Prodi::where('nama_prodi', $this->namaProdi)->first();
            if (!$prodi) {
                throw new Exception("Program studi '{$this->namaProdi}' tidak ditemukan. Periksa nama prodi pada sheet '{$sheetName}'.");
            }

            $kelas = Kelas::where([
                'nama_kelas' => $this->namaKelas,
                'angkatan' => $this->angkatan,
                'kode_prodi' => $prodi->kode_prodi,
            ])->first();

            if (!$kelas) {
                throw new Exception("Kelas '{$this->namaKelas}' untuk angkatan {$this->angkatan} dan prodi '{$this->namaProdi}' tidak ditemukan.");
            }

            $this->kelasId = $kelas->id;
            Log::info("Processed sheet name: {$sheetName}, extracted class: {$this->namaKelas}, nama_prodi: {$this->namaProdi}, kode_prodi: {$prodi->kode_prodi}, kelas_id: {$this->kelasId}");
        } else {
            $this->namaKelas = null;
            $this->namaProdi = null;
            $this->kelasId = null;
            Log::warning('No sheet name provided, kelas_id set to null');
        }
    }

    public function headingRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        Log::info('Import row', array_merge($row, ['kelas_id' => $this->kelasId]));

        // Pastikan kelasId ada
        if (!$this->kelasId) {
            throw new Exception("Data kelas tidak valid untuk sheet ini. Pastikan sheet memiliki nama yang benar seperti '1A-D3' dan kelas terdaftar.");
        }

        $requiredColumns = ['nim', 'nm_mhs'];
        foreach ($requiredColumns as $column) {
            if (!isset($row[$column]) || empty(trim($row[$column]))) {
                throw new Exception("Kolom '" . str_replace('_', ' ', $column) . "' tidak boleh kosong atau tidak ditemukan di sheet.");
            }
        }

        if (Mahasiswa::where('nim', $row['nim'])->exists()) {
            Log::warning("Duplikat NIM: {$row['nim']}, melewati baris");
            return null;
        }

        $mahasiswa = Mahasiswa::create([
            'nim' => $row['nim'],
            'nama_mhs' => $row['nm_mhs'],
            'no_ktp' => $row['no_ktp'],
            'email' => $row['e_mail'],
            'telepon' => $row['telepon'],
            'tgl_lahir' => empty($row['tgl_lahir']) ? null : $this->parseDate($row['tgl_lahir']),
            'kota_lahir' => $row['kota_lahir'],
            'jenis_kelamin' => match (strtoupper($row['jns_kelamin'])) {
                'L' => 1,
                'P' => 0,
                default => null,
            },
            'agama' => empty($row['nm_agama']) ? null : $row['nm_agama'],
            'gol_darah' => $row['nm_darah'],
            'anak_ke' => empty($row['anak_ke']) ? null : $row['anak_ke'],
            'nama_slta' => $row['nm_slta'],
            'jalur_daftar' => $row['nm_jalur_daftar'],
            'status_mhs' => $row['status_mhs'],
            'nem' => empty($row['nem']) ? null : $row['nem'],
            'kelas_id' => $this->kelasId,
        ]);

        Ayah::create([
            'nim' => $mahasiswa->nim,
            'nama_ayah' => $row['nm_ayah'],
            'pekerjaan_ayah' => $row['pekerjaan_ayah'],
            'alamat_ayah' => $row['alamat_ayah'],
            'telepon_ayah' => $row['telepon_ayah'],
            'kota_ayah' => $row['kota_ayah'],
            'pendidikan_ayah' => empty($row['pendidikan_ayah']) ? null : $row['pendidikan_ayah'],
            'instansi_ayah' => $row['instansi_ayah'],
            'telepon_instansi_ayah' => $row['telepon_instansi_ayah'],
            'kode_pos_ayah' => $row['kode_pos_ayah'],
            'penghasilan_ayah' => $row['penghasilan_ayah'],
        ]);

        Ibu::create([
            'nim' => $mahasiswa->nim,
            'nama_ibu' => $row['nm_ibu'],
            'pekerjaan_ibu' => $row['pekerjaan_ibu'],
            'alamat_ibu' => $row['alamat_ibu'],
            'telepon_ibu' => $row['telepon_ibu'],
            'kota_ibu' => $row['kota_ibu'],
            'pendidikan_ibu' => empty($row['pendidikan_ibu']) ? null : $row['pendidikan_ibu'],
            'instansi_ibu' => $row['instansi_ibu'],
            'telepon_instansi_ibu' => $row['telepon_instansi_ibu'],
            'kode_pos_ibu' => $row['kode_pos_ibu'],
            'penghasilan_ibu' => $row['penghasilan_ibu'],
        ]);

        DataTinggal::create([
            'nim' => $mahasiswa->nim,
            'alamat_tinggal' => $row['alamat_mhs'],
            'kode_pos' => $row['kode_pos'],
            'kab_kota' => $row['nm_kabupaten'],
        ]);

        return $mahasiswa;
    }

    public function sheets(): array
    {
        $this->validSheets = [];
        $possibleSheets = ['1A-D3' => '1A-D3', '1B-D3' => '1B-D3', '1C-D3' => '1C-D3', '1A-D4' => '1A-D4', '1B-D4' => '1B-D4', '1C-D4' => '1C-D4'];

        foreach ($possibleSheets as $sheetName) {
            try {
                new self($this->angkatan, $sheetName);
                $this->validSheets[$sheetName] = new self($this->angkatan, $sheetName);
                Log::info("Sheet '{$sheetName}' valid dan akan diproses.");
            } catch (Exception $e) {
                Log::warning("Sheet '{$sheetName}' tidak valid atau tidak ada: {$e->getMessage()}");
                continue;
            }
        }

        if (empty($this->validSheets)) {
            throw new Exception("Tidak ada sheet dengan format yang benar. Pastikan setidaknya satu sheet memiliki nama seperti '1A-D3', '1B-D3', atau '1C-D3' dan data kelas serta prodi terdaftar di sistem.");
        }

        return $this->validSheets;
    }

    public function onUnknownSheet($sheetName)
    {
        Log::warning("Sheet '{$sheetName}' tidak dikenali, diabaikan.");
        // Tidak perlu throw exception di sini karena sheets() sudah menangani validasi
    }

    protected function parseDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }

        $date = trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $date));
        $date = preg_replace('/\s+/', ' ', $date);
        Log::info("Parsing date: '{$date}'");

        if (is_numeric($date)) {
            try {
                $parsedDate = Carbon::createFromDate(1899, 12, 30)->addDays((int)$date);
                return $parsedDate->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning("Failed to parse date '{$date}' as Excel timestamp: {$e->getMessage()}");
            }
        }

        $parts = explode(' ', $date);
        if (count($parts) !== 3 || !is_numeric($parts[0]) || !is_numeric($parts[2])) {
            throw new Exception("Format tanggal lahir '{$date}' salah. Gunakan format 'Tanggal Bulan Tahun' dalam bahasa Inggris, contoh: '12 July 2005'. Pastikan kolom di Excel diatur sebagai teks.");
        }

        $day = (int)$parts[0];
        $month = strtolower($parts[1]);
        $year = (int)$parts[2];

        $monthMap = [
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
            'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
            'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
        ];

        if (!isset($monthMap[$month]) || $day < 1 || $day > 31 || $year < 1900 || $year > Carbon::now()->year) {
            throw new Exception("Format tanggal lahir '{$date}' salah atau tidak valid. Gunakan format 'Tanggal Bulan Tahun' dalam bahasa Inggris, contoh: '12 July 2005'. Pastikan bulan ditulis lengkap dan tahun antara 1900 dan " . Carbon::now()->year . ".");
        }

        try {
            $parsedDate = Carbon::create($year, $monthMap[$month], $day);
            return $parsedDate->format('Y-m-d');
        } catch (\Exception $e) {
            throw new Exception("Format tanggal lahir '{$date}' tidak dapat diproses. Gunakan format 'Tanggal Bulan Tahun' dalam bahasa Inggris, contoh: '12 July 2005'. Pastikan kolom di Excel diatur sebagai teks.");
        }
    }
}
