<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RateLimitNimbusWebhooks
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $key = 'nimbus_webhook_ip_' . $request->ip();

        if ($this->limiter->tooManyAttempts($key, 60)) {
            Log::channel('nimbus_security')->warning('Rate limit exceeded for Nimbus webhook', [
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Too Many Requests'], 429);
        }

        $this->limiter->hit($key, 60);

        return $next($request);
    }
}
