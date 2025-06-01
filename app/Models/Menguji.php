<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menguji extends Model
{
    protected $table = 'menguji';
    protected $primaryKey = ['kota', 'kode_dosen'];
    public $incrementing = false;

    protected $fillable = ['kota', 'kode_dosen', 'penguji_ke'];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'kota', 'kota');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }
}
