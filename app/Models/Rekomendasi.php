<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasi';
    public $incrementing = false;

    protected $fillable = ['kode_surat', 'tujuan_rekomendasi'];

    // public function surat()
    // {
    //     return $this->belongsTo(Surat::class, 'kode_surat', 'kode_surat');
    // }
}
