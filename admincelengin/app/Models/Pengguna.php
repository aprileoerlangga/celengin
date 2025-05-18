<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens;
    
    protected $fillable = ['nama', 'email', 'password', 'terakhir_login'];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pengguna) {
            if ($pengguna->password) {
                $pengguna->password = bcrypt($pengguna->password);
            }
        });

        static::updating(function ($pengguna) {
            if ($pengguna->isDirty('password')) {
                $pengguna->password = bcrypt($pengguna->password);
            }
        });
    }
}