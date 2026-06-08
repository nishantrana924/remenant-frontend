<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Base Security Headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // CSP configuration
        if (env('CSP_ENABLED', true)) {
            $cspMode = env('CSP_MODE', 'report-only');
            $cspHeader = $cspMode === 'enforce' ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only';
            
            $connectSrc = "connect-src 'self' https://lumberjack.razorpay.com https://api.razorpay.com https://www.google-analytics.com https://analytics.google.com https://stats.g.doubleclick.net";
            
            if (app()->environment('local')) {
                $connectSrc .= " wss://localhost:5173";
            }
            
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://checkout.razorpay.com https://www.googletagmanager.com https://www.google-analytics.com https://unpkg.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com; img-src 'self' data: https://badges.razorpay.com https://www.google-analytics.com https://ui-avatars.com; " . $connectSrc . "; frame-src 'self' https://api.razorpay.com; font-src 'self' https://fonts.gstatic.com data:;";
            
            $response->headers->set($cspHeader, $csp);
        }

        return $response;
    }
}
