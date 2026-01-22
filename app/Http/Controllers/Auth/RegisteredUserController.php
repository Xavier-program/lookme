<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\TermsAcceptance;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],

        ]);

        // Subir foto pública
        $photoPath = null;

        if ($request->hasFile('photo_public')) {
            $photoPath = $request->file('photo_public')->store('public/photos_public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            
           
            'role' => 'girl',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        TermsAcceptance::create([
    'user_id' => $user->id,
    'role' => $user->role,
    'accepted_at' => now(),
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),
    'terms_version' => 'v1.0',
    'accepted_from' => url()->current(),
]);


        // Redirigir al panel de chica
        return redirect()->route('girl.dashboard');
    }
}
