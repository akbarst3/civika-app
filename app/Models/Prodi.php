<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    protected $primaryKey = 'kode_prodi';

    protected $fillable = ['nama_prodi'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'kode_prodi', 'kode_prodi');
    }
}
