<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Menentukan domain mana saja yang boleh mengakses API Laravel kamu.
    | Sangat penting untuk menghubungkan React (Firebase) dan Laravel (Railway).
    |
    */

    // Mengizinkan semua route yang diawali dengan /api/
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register'],

    // Mengizinkan semua metode (GET, POST, PUT, DELETE, dll)
    'allowed_methods' => ['*'],

    // DAFTAR DOMAIN YANG DIIZINKAN (Whitelist)
    'allowed_origins' => [
        'http://localhost:5173',               // React Lokal
        'https://sehat-hidup-52205.web.app',   // Firebase Hosting
        'https://sehat-hidup-52205.firebaseapp.com', // Cadangan domain Firebase
    ],

    'allowed_origins_patterns' => [],

    // Mengizinkan semua header (Authorization, Content-Type, dll)
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /**
     * PENTING: Jika kamu menggunakan Sanctum atau mengirim Cookie/Token 
     * lewat header Authorization, ini harus TRUE.
     */
    'supports_credentials' => true,

];