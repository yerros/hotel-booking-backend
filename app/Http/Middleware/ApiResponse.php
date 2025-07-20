<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set JSON content type for API responses
        if ($request->is('api/*')) {
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Accept', 'application/json');
        }

        return $response;
    }
}