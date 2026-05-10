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

        $unpoly = new \Webstronauts\Unpoly\Unpoly();
        
        // Decorate the response with Unpoly headers/cookies
        $unpoly->decorateResponse($request, $response);

        return $response;
    }
}
