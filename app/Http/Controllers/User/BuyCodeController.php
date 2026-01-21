<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\User;
use Carbon\Carbon;

class BuyCodeController extends Controller
{
    // NUEVO MÉTODO: Mostrar vista de compra
    public function show($id)
    {
        $girl = User::findOrFail($id);
        return view('user.buy-code', compact('girl'));
    }

    public function buy($id)
    {
        $girl = User::findOrFail($id);

        // Generar código aleatorio
        $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        // Guardar con expiración de 1 hora
        $expiresAt = Carbon::now()->addHour();

        Code::create([
    'code' => $code,
    'expires_at' => $expiresAt,
]);


        return back()->with('success', "Código generado: $code (válido 1 hora)");
    }
}
