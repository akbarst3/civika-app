<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ayah extends Model
{
    protected $table = 'ayah';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim', 'nama_ayah', 'pekerjaan_ayah', 'alamat_ayah', 'telepon_ayah',
        'kota_ayah', 'instansi_ayah', 'telepon_instansi_ayah', 'kode_pos_ayah', 'penghasilan_ayah'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
