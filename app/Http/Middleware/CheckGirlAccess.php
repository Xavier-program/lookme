<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGirlAccess
{
    public function handle(Request $request, Closure $next)
    {
        $girlId = $request->route('id');

        $expiresAt = session()->get("access_girl_{$girlId}");

        // ❌ No hay acceso o expiró
        if (!$expiresAt || now()->greaterThan($expiresAt)) {
            return redirect()
                ->route('user.girls.private', $girlId)
                ->with('error', 'Tu acceso ha expirado. Ingresa un nuevo código.');
        }

        return $next($request);
    }
}
