<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUnpolyRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->hasHeader('X-Up-Target')) {
            $response->headers->set('X-Up-Location', $request->fullUrl());
            
            if ($request->method() !== 'GET') {
                $response->headers->set('X-Up-Method', $request->method());
            }
        }

        return $response;
    }
}
