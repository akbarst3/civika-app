<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_matkul';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_matkul', 'nama_matkul', 'jumlah_sks'];

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'kode_matkul', 'kode_matkul');
    }
}
