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

    public function pembimbing1()
    {
        return $this->hasOne(MembimbingKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->whereColumn('tahun', 'tahun')
            ->where('pembimbing_ke', 1);
    }

    public function pembimbing2()
    {
        return $this->hasOne(MembimbingKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->whereColumn('tahun', 'tahun')
            ->where('pembimbing_ke', 2);
    }

    public function penguji1()
    {
        return $this->hasOne(MengujiKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->whereColumn('tahun', 'tahun')
            ->where('penguji_ke', 1);
    }

    public function penguji2()
    {
        return $this->hasOne(MengujiKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->whereColumn('tahun', 'tahun')
            ->where('penguji_ke', 2);
    }

    public function membimbingKpPkl()
    {
        return $this->hasOne(MembimbingKpPkl::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function mengujiKpPkl()
    {
        return $this->hasOne(MengujiKpPkl::class, 'id_perusahaan', 'id_perusahaan');
    }
// tes
    public function pembimbing11()
    {
        return $this->hasOne(MembimbingKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->addSelect(['*']) // Force the query to include all columns
            ->whereColumn('membimbing_kp_pkl.tahun', '=', 'kp_pkl.tahun')
            ->where('pembimbing_ke', 1);
    }

    public function pembimbing22()
    {
        return $this->hasOne(MembimbingKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->addSelect(['*']) // Force the query to include all columns
            ->whereColumn('membimbing_kp_pkl.tahun', '=', 'kp_pkl.tahun')
            ->where('pembimbing_ke', 2);
    }

    public function penguji11()
    {
        return $this->hasOne(MengujiKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->addSelect(['*']) // Force the query to include all columns
            ->whereColumn('menguji_kp_pkl.tahun', '=', 'kp_pkl.tahun')
            ->where('penguji_ke', 1);
    }

    public function penguji22()
    {
        return $this->hasOne(MengujiKpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->addSelect(['*']) // Force the query to include all columns
            ->whereColumn('menguji_kp_pkl.tahun', '=', 'kp_pkl.tahun')
            ->where('penguji_ke', 2);
    }
}
