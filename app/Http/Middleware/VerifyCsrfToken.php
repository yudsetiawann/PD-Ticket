<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

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
        'midtrans/*',
    ];

    protected function tokensMatch($request)
    {
        $uri = $request->path();
        Log::info("🔒 Checking CSRF for URI: $uri");
        return parent::tokensMatch($request);
    }

    public function handle($request, \Closure $next)
    {
        $uri = $request->path();
        if ($this->inExceptArray($request)) {
            Log::info("✅ CSRF skipped for URI: $uri");
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
