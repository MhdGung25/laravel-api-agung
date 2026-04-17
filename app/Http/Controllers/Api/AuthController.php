<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle Login & Register Sync dari Firebase
     * Menangani user baru (otomatis input) dan user lama (update data)
     */
    public function loginCheck(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'firebase_uid' => 'required|string',
            'email'        => 'nullable|email', // Opsional jika hanya cek UID
            'name'         => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Cari user berdasarkan Firebase UID
            $user = User::where('firebase_uid', $request->firebase_uid)->first();

            // 3. Jika user TIDAK ditemukan berdasarkan UID, cek berdasarkan Email (untuk sinkronisasi)
            if (!$user && $request->email) {
                $user = User::where('email', $request->email)->first();
            }

            // 4. Logika Otomatis Input / Update (Otomatis Daftar jika belum ada)
            if (!$user) {
                // Jika benar-benar belum ada, buat baru
                $user = User::create([
                    'name'         => $request->name ?? 'User',
                    'email'        => $request->email,
                    'firebase_uid' => $request->firebase_uid,
                    'password'     => Hash::make($request->firebase_uid), // Password dummy
                ]);
            } else {
                // Jika sudah ada, update UID-nya (jika berubah/baru sinkron)
                $user->update([
                    'firebase_uid' => $request->firebase_uid
                ]);
            }

            // 5. Generate Token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'       => 'success',
                'message'      => 'Autentikasi Berhasil',
                'access_token' => $token,
                'user'         => $user
            ], 200);

        } catch (\Exception $e) {
            Log::error("Auth Error: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fungsi Register Sync (Opsional, bisa diarahkan ke loginCheck juga)
     */
    public function registerFromFirebase(Request $request) 
    {
        return $this->loginCheck($request);
    }
}