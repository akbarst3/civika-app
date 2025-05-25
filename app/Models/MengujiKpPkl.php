<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MengujiKpPkl extends Model
{
    protected $table = 'menguji_kp_pkl';
    protected $primaryKey = ['id_perusahaan', 'tahun', 'kode_dosen'];
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = ['id_perusahaan', 'tahun', 'kode_dosen', 'penguji_ke'];

    public function kpPkl()
    {
        return $this->belongsTo(KpPkl::class, ['id_perusahaan', 'tahun'], ['id_perusahaan', 'tahun']);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }
}
