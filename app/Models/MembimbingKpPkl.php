<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembimbingKpPkl extends Model
{
    protected $table = 'membimbing_kp_pkl';
    protected $primaryKey = ['id_perusahaan', 'tahun', 'kode_dosen'];
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = ['id_perusahaan', 'tahun', 'kode_dosen', 'pembimbing_ke'];

    public function kpPkl()
    {
        return $this->belongsTo(KpPkl::class, 'id_perusahaan', 'id_perusahaan')
            ->addSelect(['*']) // Force the query to include all columns
            ->join('membimbing_kp_pkl', function($join) {
                $join->on('kp_pkl.id_perusahaan', '=', 'membimbing_kp_pkl.id_perusahaan')
                     ->on('kp_pkl.tahun', '=', 'membimbing_kp_pkl.tahun');
            });
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }
}
