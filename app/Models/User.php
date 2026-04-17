<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi melalui Mass Assignment.
     * firebase_uid harus ada di sini agar updateOrCreate tidak error.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firebase_uid',
        'role', // Tambahkan ini jika Anda memiliki sistem role (admin/user)
    ];

    /**
     * Kolom yang disembunyikan saat data dikonversi ke JSON (untuk API).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ otomatis melakukan hashing jika diisi
    ];

    /**
     * Relasi: Satu User bisa memiliki banyak Laporan.
     * Pastikan Model Report sudah Anda buat.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id', 'id');
    }
    public function informations()
    {
        return $this->hasMany(Information::class, 'user_id', 'id');
    }
}