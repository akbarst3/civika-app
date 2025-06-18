<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'kode_dosen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_dosen', 'nip', 'nidn','nama_dosen', 'jabatan_dosen', 'ttd'];

    protected $casts = [
        'jabatan_dosen' => 'string', // Enum: Kajur, Kaprodi
    ];

    public function kpPkl()
    {
        return $this->hasMany(KpPkl::class, 'kode_dosen', 'kode_dosen');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'kode_dosen', 'kode_dosen');
    }

    public function surat()
    {
        return $this->hasMany(Surat::class, 'kode_dosen', 'kode_dosen');
    }

    public function membimbing()
    {
        return $this->hasMany(Membimbing::class, 'kode_dosen', 'kode_dosen');
    }

    public function menguji()
    {
        return $this->hasMany(Menguji::class, 'kode_dosen', 'kode_dosen');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'kode_dosen', 'kode_dosen');
    }
}