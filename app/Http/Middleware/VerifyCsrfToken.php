<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/midtrans/notification',
        'midtrans/notification',
        '/api/midtrans/notification',
        'api/midtrans/notification',
    ];

    public function handle($request, \Closure $next)
    {
        if ($request->is('midtrans/notification')) {
            Log::info('✅ Midtrans callback diterima tanpa CSRF');
        }

        return parent::handle($request, $next);
    }
}
