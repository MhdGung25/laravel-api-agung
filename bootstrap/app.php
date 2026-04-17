<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mengecualikan jalur API dari verifikasi CSRF agar React bisa POST
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        // Jika error "Target class... does not exist" muncul, 
        // pastikan kita tidak memanggil statefulApi() jika Sanctum belum terinstall sempurna.
        // Untuk alur Firebase, ini bisa dikomentari/dihapus:
        // $middleware->statefulApi(); 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();