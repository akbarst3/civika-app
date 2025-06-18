<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim', 'nama_mhs', 'no_ktp', 'email', 'telepon',
        'tgl_lahir', 'kota_lahir', 'jenis_kelamin', 'agama', 'gol_darah', 'anak_ke',
        'nama_slta', 'jalur_daftar', 'nem', 'kelas_id'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'jenis_kelamin' => 'boolean', // Enum: 0 = Laki-laki, 1 = Perempuan
        'gol_darah' => 'string', // Enum: A, B, AB, O
        'agama' => 'string', // Enum: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu
        'jalur_daftar' => 'string', // Enum: SNMPTN, SBMPTN, Mandiri, Lainnya
        'nem' => 'decimal:2'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'nim', 'nim');
    }

    public function ayah()
    {
        return $this->hasOne(Ayah::class, 'nim', 'nim');
    }

    public function ibu()
    {
        return $this->hasOne(Ibu::class, 'nim', 'nim');
    }

    public function dataTinggal()
    {
        return $this->hasOne(DataTinggal::class, 'nim', 'nim');
    }

    public function indeksPrestasiSemester()
    {
        return $this->hasMany(IndeksPrestasiSemester::class, 'nim', 'nim');
    }

    public function kpPkl()
    {
        return $this->hasOne(KpPkl::class, 'nim', 'nim');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'nim', 'nim');
    }

    public function surat()
    {
        return $this->hasMany(Surat::class, 'nim', 'nim');
    }

    public function tugasAkhir()
    {
        return $this->hasMany(TugasAkhir::class, 'nim', 'nim');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'nim', 'nim');
    }
}
