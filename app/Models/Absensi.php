<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = ['nim', 'semester'];
    public $incrementing = false;

    protected $fillable = ['nim', 'semester', 'jml_sakit', 'jml_izin', 'jml_alfa', 'nilai_penghayatan'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
