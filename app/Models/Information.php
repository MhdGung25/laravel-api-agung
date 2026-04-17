<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    /**
     * Nama tabel diatur manual karena Laravel secara default 
     * mencari 'information' (tanpa 's' di akhir).
     */
    protected $table = 'informations';

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_active'
    ];

    /**
     * Melakukan casting otomatis agar nilai 0/1 dari database 
     * dibaca sebagai false/true di React/Frontend.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: Satu Informasi dipublikasikan oleh satu User (Admin/Pengurus).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope untuk mengambil hanya informasi yang sedang aktif.
     * Digunakan pada Dashboard: Information::active()->count();
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}