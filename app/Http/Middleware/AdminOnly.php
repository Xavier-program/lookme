<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Si NO es admin, bloquear acceso
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}
