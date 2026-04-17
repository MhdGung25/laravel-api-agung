<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan pendaftaran tabel ke database.
     */
    public function up(): void
    {
        // 1. Tabel Users (Sudah disesuaikan untuk Firebase)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_uid')->unique(); // ID unik sebagai penyambung ke Firebase Auth
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            
            // Password dibuat nullable jika kamu murni pakai Firebase Auth, 
            // karena password sebenarnya disimpan di server Google (Firebase).
            $table->string('password')->nullable(); 
            
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabel Password Reset Tokens (Bawaan Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Tabel Sessions (Bawaan Laravel untuk handling session)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Batalkan migration (Hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};