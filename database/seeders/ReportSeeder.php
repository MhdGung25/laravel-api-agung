<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report; // Tambahkan import ini agar tidak error "not found"
use App\Models\User;

class ReportSeeder extends Seeder
{
    public function run()
    {
        // Ambil user pertama yang ada di database (siapa pun itu, misal Agung)
        $user = User::first();

        if ($user) {
            Report::create([
                'user_id' => $user->id,
                'title' => 'Laporan Lampu Jalan Mati',
                'description' => 'Lampu di blok C18 padam sejak kemarin malam.',
                'status' => 'pending'
            ]);

            Report::create([
                'user_id' => $user->id,
                'title' => 'Pendaftaran Karang Taruna',
                'description' => 'Data anggota baru untuk RW 18.',
                'status' => 'process'
            ]);
            
            $this->command->info("Seeding berhasil untuk user: " . $user->name);
        } else {
            $this->command->error("Gagal: Tidak ada user di database. Silakan register dulu!");
        }
    }
}