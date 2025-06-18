<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
/**
 * @property string $role
 */

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'id_user', 'nim', 'kode_dosen', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'kode_dosen', 'kode_dosen');
    }

    // Logika role
    public function getRoleAttribute()
    {
        if ($this->nim) {
            return 'mahasiswa';
        } elseif ($this->kode_dosen) {
            return 'dosen';
        } else {
            return 'tata_usaha';
        }
    }
    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    // Cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}