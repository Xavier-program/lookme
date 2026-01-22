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
        if (now()->greaterThan($code->used_at->copy()->addHour())) {
            return back()->with('error', 'El acceso con este código ya expiró.');
        }

    } else {
    /**
     * ✅ PRIMER USO DEL CÓDIGO
     */
    $code->update([
        'girl_id'    => $girl->id,
        'used_at'    => now(),
        'expires_at' => now()->addHour(),
        'ip'         => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // 🔁 REFRESCAR EL MODELO PARA QUE SE ACTUALICE EN MEMORIA
    $code->refresh();
}


    // 🕐 Guardar acceso real por 1 hora
    session()->put(
    "access_girl_{$girl->id}",
    $code->used_at->copy()->addHour()
);

    return redirect()->route('user.girls.privateContent', $girl->id);
}



    // 3) Mostrar contenido privado (Paso 7)
    public function privateContent($id)
    {


        
        $girl = User::findOrFail($id);

        $expiresAt = session()->get("access_girl_{$girl->id}");

        if (!$expiresAt || now()->greaterThan($expiresAt)) {
            return redirect()->route('user.girls.private', $girl->id)
                ->with('error', 'Tu acceso expiró. Compra otro código.');
        }

        return view('user.girls.privateContent', compact('girl'));
    }

    public function index()
{
    $girls = User::where('role', 'girl')->get();

    // ⏳ Guardamos los tiempos de acceso por chica
    $accessTimes = [];
    $hasAccess = [];   // <-- AQUI

    foreach ($girls as $girl) {

        // BUSCAMOS EL ÚLTIMO CÓDIGO ASIGNADO A ESA CHICA
        $code = Code::where('girl_id', $girl->id)
            ->whereNotNull('used_at')
            ->orderByDesc('used_at')
            ->first();

        // SI EXISTE Y NO HA EXPIRADO, GUARDAMOS EL TIEMPO
        if ($code && $code->expires_at && now()->lt($code->expires_at)) {
            $accessTimes[$girl->id] = $code->expires_at->timestamp;
            $hasAccess[$girl->id] = true;  // <-- AQUI
        } else {
            $hasAccess[$girl->id] = false; // <-- AQUI
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

    if ($code->used_at && now()->greaterThan($code->used_at->copy()->addHour())) {
        return response()->json([
            'success' => false,
            'debug' => 'CÓDIGO YA EXPIRO LA HORA'
        ]);
    }

    if (!$code->used_at) {
        $code->update([
            'girl_id'    => $girl->id,
            'used_at'    => now(),
            'expires_at' => now()->addHour(),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    session()->put("access_girl_{$girl->id}", now()->addHour());

    return response()->json([
        'success' => true,
        'debug' => 'CÓDIGO VALIDO Y ASIGNADO'
    ]);
}






    
}
