<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Kita masukkan 'description' (sesuai database Anda) dan 'attachment' untuk foto laporan.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'attachment'
    ];

    /**
     * Nilai default untuk atribut tertentu.
     * Menjamin status laporan baru selalu 'pending' jika tidak diisi.
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Relasi: Satu Laporan dimiliki oleh satu User (Penduduk/Pengurus).
     * Memungkinkan kita memanggil $report->user->name di Dashboard.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * SCOPE: Memudahkan filter data di Controller/Dashboard.
     */

    // Mengambil laporan yang statusnya pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Mengambil laporan yang statusnya sedang diproses
    public function scopeProses($query)
    {
        return $query->where('status', 'proses');
    }

    // Mengambil laporan yang sudah selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}