<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Code; // <-- si tienes tabla codes
use Illuminate\Http\Request;
use App\Models\CodeUsage;

class GirlController extends Controller
{
    // 1) Mostrar formulario de código
    public function private($id)
    {
        $girl = User::findOrFail($id);
        return view('user.girls.private', compact('girl'));
    }

    // 2) Validar código (checkCode)
  public function checkCode(Request $request, $id)
{
    $request->validate([
        'code' => 'required|string',
    ]);

    $girl = User::findOrFail($id);

    $code = Code::where('girl_id', $girl->id)
        ->where('code', $request->code)
        ->where('expires_at', '>', now())
        ->first();

    if (!$code) {
        return redirect()->back()->with('error', 'Código inválido o expirado.');
    }

    // Registrar IP y user-agent
    $code->ip = request()->ip();
    $code->user_agent = request()->userAgent();
    $code->used_at = now();
    $code->save();

    // Crear sesión temporal
    session()->put("access_girl_{$girl->id}", $code->expires_at);

    return redirect()->route('user.girls.full', $girl->id);
}



    // 3) Mostrar contenido privado (Paso 7)
   public function privateContent($id)
{
    $girl = User::findOrFail($id);

    // Buscar un código activo en DB para esta chica
    $code = Code::where('girl_id', $girl->id)
        ->whereNotNull('used_at')
        ->where('expires_at', '>', now())
        ->orderByDesc('expires_at')
        ->first();

    // Si no hay código válido, eliminar sesión y redirigir al formulario
    if (!$code) {
        session()->forget("access_girl_{$girl->id}");
        return redirect()->route('user.girls.private', $girl->id)
            ->with('error', 'Tu acceso expiró. Compra otro código.');
    }

    // OPCIONAL: validar IP / user-agent para que la URL no funcione en otro dispositivo
    if ($code->ip !== request()->ip() || $code->user_agent !== request()->userAgent()) {
        session()->forget("access_girl_{$girl->id}");
        return redirect()->route('user.girls.private', $girl->id)
            ->with('error', 'Este código solo puede usarse desde el dispositivo original.');
    }

    // Sincronizar sesión con expiración real de la DB (para el contador JS)
    session()->put("access_girl_{$girl->id}", $code->expires_at);

    return view('user.girls.privateContent', compact('girl'));
}



    public function index()
    {
        $girls = User::where('role', 'girl')->get();

        $accessTimes = [];
        $hasAccess = [];

        foreach ($girls as $girl) {

            // 1) Revisar si hay acceso en sesión
            $expiresAtSession = session()->get("access_girl_{$girl->id}");

            if ($expiresAtSession && now()->lessThan($expiresAtSession)) {
                $hasAccess[$girl->id] = true;
                $accessTimes[$girl->id] = $expiresAtSession->timestamp;
                continue;
            }

            // 2) Si no hay sesión, revisar si hay un código activo en DB
            $code = Code::where('girl_id', $girl->id)
                ->whereNotNull('used_at')
                ->where('expires_at', '>', now())
                ->orderByDesc('expires_at') // <-- corregido: tomar expiración más reciente
                ->first();

            if ($code) {
                $hasAccess[$girl->id] = true;
                $accessTimes[$girl->id] = $code->expires_at->timestamp;

                // Guardar también en sesión para evitar volver a pedir
                session()->put("access_girl_{$girl->id}", $code->expires_at);
            } else {
                $hasAccess[$girl->id] = false;
            }
        }

        return view('user.girls.index', compact('girls', 'accessTimes', 'hasAccess'));
    }

      // ✅ Full profile ajustado: ahora bloquea sin código activo
 public function fullProfile($id)
{
    $girl = User::findOrFail($id);
    $sessionKey = "access_girl_{$girl->id}";

    // 1) Revisar sesión activa
    $expiresAtSession = session()->get($sessionKey);
    if ($expiresAtSession && now()->lessThan($expiresAtSession)) {
        return view('user.girls.full', compact('girl'));
    }

    // 2) Revisar si hay un código válido en la DB
    $code = Code::where('girl_id', $girl->id)
        ->whereNotNull('used_at')
        ->where('expires_at', '>', now())
        ->orderByDesc('expires_at')
        ->first();

    if (!$code) {
        // Redirigir a lista de chicas si no hay código válido
        return redirect()->route('user.girls.index')
            ->with('error', 'Debes ingresar un código válido para ver el perfil completo.');
    }

    // 3) Validar que el código se use desde el mismo dispositivo
    if ($code->ip !== request()->ip() || $code->user_agent !== request()->userAgent()) {
        return redirect()->route('user.girls.index')
            ->with('error', 'Este código solo puede usarse desde el dispositivo original.');
    }

    // 4) Si todo es válido, crear sesión temporal
    session()->put($sessionKey, $code->expires_at);

    // 5) Mostrar contenido privado
    return view('user.girls.full', compact('girl'));
}





    public function checkCodeAjax(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $girl = User::findOrFail($id);

        $code = Code::where('code', $request->code)
            ->where(function ($query) use ($girl) {
                $query->whereNull('girl_id')
                      ->orWhere('girl_id', $girl->id);
            })
            ->first();

        // DEBUG TEMPORAL
        if (!$code) {
            return response()->json([
                'success' => false,
                'debug' => 'NO ENCUENTRA EL CÓDIGO o ya está asignado a otra chica'
            ]);
        }

        if ($code->expires_at && $code->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'debug' => 'CÓDIGO EXPIRADO (expires_at)'
            ]);
        }

        if ($code->used_at && $code->girl_id != $girl->id) {
            return response()->json([
                'success' => false,
                'debug' => 'CÓDIGO YA USADO EN OTRA CHICA'
            ]);
        }

        if ($code->used_at && now()->greaterThan($code->used_at->copy()->addMinutes(30))) {
            return response()->json([
                'success' => false,
                'debug' => 'CÓDIGO YA EXPIRO LA HORA'
            ]);
        }

        if (!$code->used_at) {
            $expiresAt = now()->addMinutes(30); // unificar expiración
            $code->update([
                'girl_id'    => $girl->id,
                'used_at'    => now(),
                'expires_at' => $expiresAt,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Guardar sesión con la misma expiración
            session()->put("access_girl_{$girl->id}", $expiresAt);
        } else {
            // Si ya estaba usado pero es válido, asegurar que la sesión también tenga el mismo tiempo
            session()->put("access_girl_{$girl->id}", $code->expires_at);
        }

        return response()->json([
            'success' => true,
            'debug' => 'CÓDIGO VALIDO Y ASIGNADO'
        ]);
    }

    public function dashboard()
    {
        $girl = auth()->user(); 

        // Traer historial de códigos usados para esta chica
        $history = CodeUsage::where('girl_id', $girl->id)
                    ->orderByDesc('used_at')
                    ->with('code')
                    ->get();

        return view('girl.dashboard', compact('girl', 'history'));
    }
}
