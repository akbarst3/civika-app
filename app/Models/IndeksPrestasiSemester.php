<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndeksPrestasiSemester extends Model
{
    protected $table = 'indeks_prestasi_semester';
    protected $primaryKey = ['nim', 'semester'];
    public $incrementing = false;

    protected $fillable = ['nim', 'semester', 'status', 'indeks_prestasi', 'nilai_bobot', 'jumlah_d', 'keterangan'];

    protected $casts = [
        'status' => 'string', // Enum: LL, TT, T, DO, MG
        'indeks_prestasi' => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
