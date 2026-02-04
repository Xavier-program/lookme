<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGirlAccess
{
    public function handle(Request $request, Closure $next)
    {
        $girlId = $request->route('id');

        // acceso guardado en sesión
        $access = session("girl_access.$girlId");

        // ❌ No existe acceso
        if (!$access) {
            return redirect()
                ->route('user.girls.index')
                ->with('error', 'Acceso no autorizado');
        }

        // ❌ Expirado
        if (now()->greaterThan($access['expires_at'])) {
            session()->forget("girl_access.$girlId");

            return redirect()
                ->route('user.girls.index')
                ->with('error', 'Tu acceso ha expirado');
        }

        return $next($request);
    }
}
