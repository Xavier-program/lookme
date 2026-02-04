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
            'code' => 'required|string'
        ]);

        $girl = User::findOrFail($id);

        $code = Code::where('code', $request->code)
                ->where(function ($query) use ($girl) {
                    $query->whereNull('girl_id')
                          ->orWhere('girl_id', $girl->id);
                })
                ->first();

        // ❌ No existe
        if (!$code) {
            return back()->with('error', 'Código inválido.');
        }

        // ⛔ Código caducado (del sistema, NO la hora)
        if ($code->expires_at && $code->expires_at->isPast()) {
            return back()->with('error', 'El código ha expirado.');
        }

        /**
         * 🔒 SI YA FUE USADO
         */
        if ($code->used_at) {

            // ❌ Si pertenece a otra chica
            if ($code->girl_id != $girl->id) {
                return back()->with('error', 'Este código no pertenece a esta chica.');
            }

            // ⛔ Si ya pasó la hora
            if (now()->greaterThan($code->used_at->copy()->addMinutes(30))) {
                return back()->with('error', 'El acceso con este código ya expiró.');
            }

        } else {
            /**
             * ✅ PRIMER USO DEL CÓDIGO
             */
            $expiresAt = now()->addMinutes(30); // unificar expiración
            $code->update([
                'girl_id'    => $girl->id,
                'used_at'    => now(),
                'expires_at' => $expiresAt,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 🔁 REFRESCAR EL MODELO PARA QUE SE ACTUALICE EN MEMORIA
            $code->refresh();

            // 🕐 Guardar acceso en sesión con el mismo tiempo que la DB
            session()->put("access_girl_{$girl->id}", $expiresAt);
        }

        // 🔥 AQUI SE GUARDA EL HISTORIAL (SE AGREGO ESTA PARTE)
        CodeUsage::create([
            'code_id'    => $code->id,
            'girl_id'    => $girl->id,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'used_at'    => now(),
        ]);

        // 🔐 Generar token único temporal
$token = bin2hex(random_bytes(16)); // 32 caracteres, imposible de adivinar

// Guardar token en sesión (NO afecta tu lógica actual)
session()->put("girl_token_{$token}", [
    'girl_id' => $girl->id,
    'expires_at' => now()->addMinutes(30),
]);

// Redirigir usando token (NO usando ID)
return redirect()->route('girls.token', ['token' => $token]);

    }






    // 3) Mostrar contenido privado (Paso 7)
    public function privateContent($id)
{
    $girl = User::findOrFail($id);

    // 1️⃣ Revisar acceso en sesión
    $expiresAt = session()->get("access_girl_{$girl->id}");

    // 2️⃣ Validar también contra la DB
    $code = Code::where('girl_id', $girl->id)
        ->whereNotNull('used_at')
        ->where('expires_at', '>', now())
        ->orderByDesc('expires_at')
        ->first();

    if (!$expiresAt || !$code || now()->greaterThan($expiresAt) || now()->greaterThan($code->expires_at)) {
        // Limpiar sesión si ya expiró
        session()->forget("access_girl_{$girl->id}");

        return redirect()->route('user.girls.private', $girl->id)
            ->with('error', 'Tu acceso expiró. Compra otro código.');
    }

    // Sincronizar sesión con expiración real de la DB
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

   public function fullProfile($id)
{
    $girl = User::findOrFail($id);
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







    public function accessByToken($token)
{
    // Revisar token en sesión
    $data = session()->get("girl_token_{$token}");

    if (!$data) {
        return redirect()->route('user.girls.index')
            ->with('error', 'Acceso no autorizado o expirado.');
    }

    // Revisar expiración
    if (now()->greaterThan($data['expires_at'])) {
        session()->forget("girl_token_{$token}");
        return redirect()->route('user.girls.index')
            ->with('error', 'El acceso ha expirado.');
    }

    // Obtener la chica
    $girl = User::find($data['girl_id']);
    if (!$girl) {
        return redirect()->route('user.girls.index')
            ->with('error', 'Chica no encontrada.');
    }

    // ✅ Sincronizar sesión de acceso si quieres mantener timers
    session()->put("access_girl_{$girl->id}", $data['expires_at']);

    // Mostrar vista completa
    return view('user.girls.full', compact('girl'));
}

}
