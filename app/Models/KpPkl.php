<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpPkl extends Model
{
    protected $table = 'kp_pkl';
    protected $primaryKey = ['id_perusahaan', 'tahun'];
    public $incrementing = false;

    protected $fillable = ['id_perusahaan', 'tahun', 'nim', 'kode_dosen', 'nama_perusahaan'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function membimbingKpPkl()
    {
        return $this->hasOne(MembimbingKpPkl::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function mengujiKpPkl()
    {
        return $this->hasOne(MengujiKpPkl::class, 'id_perusahaan', 'id_perusahaan');
    }
}
