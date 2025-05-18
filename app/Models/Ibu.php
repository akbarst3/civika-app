<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ibu extends Model
{
    protected $table = 'ibu';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim', 'nama_ibu', 'pekerjaan_ibu', 'alamat_ibu', 'telepon_ibu',
        'kota_ibu', 'instansi_ibu', 'telepon_instansi_ibu', 'kode_pos_ibu', 'penghasilan_ibu'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
