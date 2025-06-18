<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhir';
    protected $primaryKey = 'kota';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kota', 'topik'];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kota', 'kota'); // Relasi ke banyak mahasiswa berdasarkan kota
    }

    public function membimbing()
    {
        return $this->hasMany(Membimbing::class, 'kota', 'kota');
    }

    public function menguji()
    {
        return $this->hasMany(Menguji::class, 'kota', 'kota');
    }
}
