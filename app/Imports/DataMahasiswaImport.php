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
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Illuminate\Support\Facades\Log;
use Exception;

class DataMahasiswaImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    protected string $angkatan;
    protected ?string $namaKelas;

    public function __construct($angkatan, $sheetName = null)
    {
        $this->angkatan = $angkatan;
        if ($sheetName) {
            $parts = explode('-', $sheetName);
            $classPart = $parts[0] ?? null;
            $this->namaProdi = $parts[1] ?? null;
            $this->namaKelas = $classPart ? preg_replace('/\d+/', '', $classPart) : null;

            if ($this->namaKelas && $this->namaProdi) {
                $prodi = Prodi::where('nama_prodi', $this->namaProdi)->first();
                if (!$prodi) {
                    Log::warning("Nama prodi '{$this->namaProdi}' tidak ditemukan di tabel prodi untuk sheet: {$sheetName}");
                    $this->kelasId = null;
                    return;
                }

                $kelas = Kelas::where([
                    'nama_kelas' => $this->namaKelas,
                    'angkatan' => $this->angkatan,
                    'kode_prodi' => $prodi->kode_prodi,
                ])->first();

                if (!$kelas) {
                    Log::warning("Kelas dengan nama_kelas: {$this->namaKelas}, angkatan: {$this->angkatan}, kode_prodi: {$prodi->kode_prodi} tidak ditemukan untuk sheet: {$sheetName}");
                    $this->kelasId = null;
                    return;
                }

                $this->kelasId = $kelas->id;
                Log::info("Processed sheet name: {$sheetName}, extracted class: {$this->namaKelas}, nama_prodi: {$this->namaProdi}, kode_prodi: {$prodi->kode_prodi}, kelas_id: {$this->kelasId}");
            } else {
                $this->kelasId = null;
                Log::warning("Invalid sheet name format: {$sheetName}, kelas_id set to null");
            }
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

        if (Mahasiswa::where('nim', $row['nim'])->exists()) {
            Log::warning("Duplicate NIM: {$row['nim']}, skipping row");
            return null;
        }

        if (!$this->kelasId) {
            Log::warning("No kelas_id available for row with NIM: {$row['nim']}, skipping row");
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
        return [
            '1A-D3' => new self($this->angkatan, '1A-D3'),
            '1B-D3' => new self($this->angkatan, '1B-D3'),
            '1C-D3' => new self($this->angkatan, '1C-D3'),
        ];
    }

    protected function parseDate($date): ?string
    {
        try {
            return \Carbon\Carbon::createFromFormat('d F Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning("Invalid date format for tgl_lahir: {$date}, setting to null");
            return null;
        }
    }
}
