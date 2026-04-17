<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('informations', function (Blueprint $table) {
            $table->id();
            // User yang memposting (biasanya Admin RW)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Judul Pengumuman (misal: "Iuran Agustus")
            $table->text('content'); // Isi detail pengumuman
            $table->boolean('is_active')->default(true); // Status Aktif/Arsip
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('informations');
    }
};