<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CodeBatch;


class AdminGirlController extends Controller
{
    // LISTADO
    public function index()
    {
        $girls = User::where('role', 'girl')->get();

        return view('admin.girls.index', compact('girls'));
    }

    // DETALLE
    public function show(User $user)
    {
        return view('admin.girls.show', [
            'user' => $user
        ]);
    }




    public function destroy(User $girl)
{
    // BORRAR ARCHIVOS DEL STORAGE (opcional pero recomendado)
    if ($girl->photo_public) {
        \Illuminate\Support\Facades\Storage::delete($girl->photo_public);
    }

    for ($i = 1; $i <= 6; $i++) {
        if ($girl->{"photo_private_$i"}) {
            \Illuminate\Support\Facades\Storage::delete($girl->{"photo_private_$i"});
        }
    }

    if ($girl->video_private) {
        \Illuminate\Support\Facades\Storage::delete($girl->video_private);
    }

    // BORRAR USUARIO
    $girl->delete();

    return redirect()->route('girls.index')
        ->with('success', 'Chica eliminada correctamente.');
}




//eliminar contenido


public function deletePhotoPublic(User $girl)
{
    if ($girl->photo_public) {
        Storage::delete($girl->photo_public);
        $girl->photo_public = null;
        $girl->save();
    }

    return back()->with('success', 'Foto pública eliminada');
}

public function deletePhotoPrivate(User $girl, $index)
{
    $field = "photo_private_$index";

    if ($girl->{$field}) {
        Storage::delete($girl->{$field});
        $girl->{$field} = null;
        $girl->save();
    }

    return back()->with('success', 'Foto privada eliminada');
}

public function deleteVideoPrivate(User $girl)
{
    if ($girl->video_private) {
        Storage::delete($girl->video_private);
        $girl->video_private = null;
        $girl->save();
    }

    return back()->with('success', 'Video eliminado');
}

public function deleteDescriptionPublic(User $girl)
{
    $girl->description_public = null;
    $girl->save();

    return back()->with('success', 'Descripción pública eliminada');
}

public function deleteDescriptionPrivate(User $girl)
{
    $girl->description_private = null;
    $girl->save();

    return back()->with('success', 'Descripción privada eliminada');
}



public function generateCodes(Request $request)
{
    $request->validate([
        'amount' => 'required|integer|min:1'
    ]);

    $amount = $request->amount;

    // Crear batch
    $batch = CodeBatch::create([
        'quantity' => $amount
    ]);

    // Generar códigos
    for ($i = 0; $i < $amount; $i++) {
        $code = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        Code::create([
            'code' => $code,
            'expires_at' => Carbon::now()->addWeek(),

            'batch_id' => $batch->id
        ]);
    }

    // Redirigir a la tabla tipo excel
    return redirect()->route('admin.codes.show', $batch->id);
}





}
