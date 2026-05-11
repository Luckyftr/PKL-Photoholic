<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Semua kolom yang bisa diisi manual saat pembuatan data harus didaftarkan di sini.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',  
        'photo',    
        'password',
        'role',
        'status',
        'google_id',
    ];

    /**
     * Kolom ini tidak akan ditampilkan saat data diubah menjadi JSON (misal untuk API) demi keamanan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mengubah tipe data secara otomatis. Misalnya password otomatis di-hash (enkripsi) oleh Laravel.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}