<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- IMPORTANTE
use App\Models\CodeUsage; // <-- AGREGAR ESTO
use App\Models\Code;
use Illuminate\Support\Facades\Auth;

class GirlPanelController extends Controller
{
    public function index()
    {
        $user = auth()->user();

          $user = auth()->user();

    // 🎯 Traer códigos usados por esta chica
    $history = CodeUsage::where('girl_id', $user->id)
        ->orderByDesc('used_at')
        ->get();

    return view('chica.panel', compact('user', 'history'));
    }

    // PERFIL PÚBLICO
    public function editPublic()
    {
        $user = auth()->user();
        return view('chica.editar', compact('user'));
    }

    public function updatePublic(Request $request)
    {
        $request->validate([
            'name_artist' => 'required|string|max:255',
            'photo_public' => 'nullable|image|max:1024',
            'description_public' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        if ($request->hasFile('photo_public')) {
            $user->photo_public = $request->file('photo_public')->store('photos_public', 'public');
        }

        $user->name_artist = $request->name_artist;
        $user->description_public = $request->description_public;
        $user->save();

        return redirect()->route('girl.dashboard')->with('success', 'Perfil público actualizado');
    }

    // PERFIL PRIVADO
    public function editPrivate()
    {
        $user = auth()->user();
        return view('chica.private', compact('user'));
    }

    public function updatePrivate(Request $request)
    {
        $request->validate([
            'description_private' => 'nullable|string|max:1000',
            'photo_private_1' => 'nullable|image|max:1024',
            'photo_private_2' => 'nullable|image|max:1024',
            'photo_private_3' => 'nullable|image|max:1024',
            'photo_private_4' => 'nullable|image|max:1024',
            'photo_private_5' => 'nullable|image|max:1024',
            'photo_private_6' => 'nullable|image|max:1024',
            'video_private' => 'nullable|mimes:mp4|max:10240',
        ]);

        $user = auth()->user();

        for ($i = 1; $i <= 6; $i++) {
            if ($request->hasFile("photo_private_$i")) {
                $user->{"photo_private_$i"} = $request->file("photo_private_$i")
                    ->store('photos_private', 'public');
            }
        }

        if ($request->hasFile('video_private')) {
            $user->video_private = $request->file('video_private')
                ->store('videos_private', 'public');
        }

        $user->description_private = $request->description_private;
        $user->save();

        return redirect()->route('girl.dashboard')->with('success', 'Perfil privado actualizado');
    }

    // ELIMINAR FOTO PÚBLICA
    public function deletePhotoPublic()
    {
        $user = auth()->user();

        if ($user->photo_public) {
            Storage::disk('public')->delete($user->photo_public);
            $user->photo_public = null;
            $user->save();
        }

        return back()->with('success', 'Foto pública eliminada');
    }

    // ELIMINAR FOTO PRIVADA
    public function deletePhotoPrivate($index)
    {
        $user = auth()->user();

        $field = "photo_private_{$index}";

        if ($user->{$field}) {
            Storage::delete($user->{$field});
        }

        $user->{$field} = null;
        $user->save();

        return redirect()->route('chica.edit.private');
    }

    // ELIMINAR VIDEO PRIVADO
    public function deleteVideoPrivate()
    {
        $user = auth()->user();

        if ($user->video_private) {
            Storage::disk('public')->delete($user->video_private);
            $user->video_private = null;
            $user->save();
        }

        return back()->with('success', 'Video privado eliminado');
    }




public function history()
{
    $girl = Auth::user(); // la chica logueada

    $history = Code::where('girl_id', $girl->id)
        ->whereNotNull('used_at')
        ->orderBy('used_at', 'desc')
        ->get();

    return view('chica.history-codes', compact('history'));
}

}
