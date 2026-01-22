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

        // Busca el código en tu tabla codes (GENERAL, no por chica)
        $code = Code::where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$code) {
            return back()->with('error', 'Código inválido o expirado.');
        }

        // Si ya se usó este código en cualquier chica
        $alreadyUsed = \DB::table('code_usages')
            ->where('code_id', $code->id)
            ->exists();

        if ($alreadyUsed) {
            return back()->with('error', 'Este código ya fue usado en este perfil.');
        }

        // MARCAR COMO USADO y expirar en 1 hora
        $code->update([
            'used_at' => now(),
            'expires_at' => now()->addHour(),
            'girl_id' => $girl->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Guardar uso en code_usages
        \DB::table('code_usages')->insert([
            'code_id' => $code->id,
            'girl_id' => $girl->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'used_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Guardar acceso en sesión por 1 hora
        session()->put("access_girl_{$girl->id}", now()->addHour());

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
        return view('user.girls.index', compact('girls'));
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

        $code = Code::where('code', $request->code)
                    ->where('expires_at', '>', now())
                    ->first();

        if (!$code) {
            return response()->json(['success' => false]);
        }

        // Si ya se usó en cualquier chica
        if (CodeUsage::where('code_id', $code->id)->exists()) {
            return response()->json(['success' => false]);
        }

        // Si llega aquí, el código es válido
        return response()->json(['success' => true]);
    }



    
}
