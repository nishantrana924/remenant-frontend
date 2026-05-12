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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Identify Layout Identity
        // We use versions to trigger full reloads on layout change
        $isDashboard = $request->is('admin') || $request->is('admin/*') || $request->is('dashboard');
        $revision = $isDashboard ? 'layout-admin-v1.1' : 'layout-public-v1.1';
        
        $response->headers->set('X-Up-Assets-Revision', $revision);

        $unpoly = new \Webstronauts\Unpoly\Unpoly();
        $unpoly->decorateResponse($request, $response);

        return $response;
    }
}
