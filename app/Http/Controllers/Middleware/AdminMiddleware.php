<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // I-check kung naka-login at kung ang role ay 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        
        return redirect('/dashboard')->with('error', 'You are not authorized to access this page.');
    }
}