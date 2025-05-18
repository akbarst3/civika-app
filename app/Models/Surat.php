<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    protected $primaryKey = 'kode_surat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_surat', 'tujuan_rekomendasi', 'nama_perusahaan', 'program',
        'id_user', 'nim', 'kode_dosen', 'judul_surat', 'jenis_surat',
        'tgl_surat', 'isi_surat', 'pesan', 'status_surat'
    ];

    protected $casts = [
        'tgl_surat' => 'date',
        'jenis_surat' => 'string', // Enum: Rekomendasi, Beasiswa, Pengantar, Lainnya
        'status_surat' => 'string', // Enum: Draft, Disetujui, Ditolak, Proses
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function beasiswa()
    {
        return $this->hasOne(Beasiswa::class, 'kode_surat', 'kode_surat');
    }

    public function rekomendasi()
    {
        return $this->hasOne(Rekomendasi::class, 'kode_surat', 'kode_surat');
    }
}
