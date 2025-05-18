<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $table = 'beasiswa';
    public $incrementing = false;

    protected $fillable = ['kode_surat', 'nama_perusahaan', 'program'];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'kode_surat', 'kode_surat');
    }
}
