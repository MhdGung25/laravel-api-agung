<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk menambah kolom firebase_uid.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada sebelum menambahkannya
            if (!Schema::hasColumn('users', 'firebase_uid')) {
                $table->string('firebase_uid')
                      ->unique()
                      ->nullable()
                      ->after('email'); // Menaruh kolom setelah email agar rapi
            }
        });
    }

    /**
     * Batalkan migration (Rollback).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu apakah kolom ada sebelum menghapusnya
            if (Schema::hasColumn('users', 'firebase_uid')) {
                $table->dropColumn('firebase_uid');
            }
        });
    }
};