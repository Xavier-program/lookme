<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Code; // <-- si tienes tabla codes
use Illuminate\Http\Request;

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

        // Busca el código en tu tabla codes
        $code = Code::where('user_id', $girl->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$code) {
            return back()->with('error', 'Código inválido o expirado.');
        }

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




}
