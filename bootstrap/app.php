<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', // ⬅️ penting: tambahkan ini!
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ TAMBAHKAN ALIAS ANDA DI SINI
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
        // Nonaktifkan CSRF khusus route Midtrans
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
            'api/midtrans/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
