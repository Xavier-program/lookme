@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4 max-w-xl">
    <div class="bg-white shadow rounded-xl overflow-hidden">

        <div class="p-6">
            <h2 class="text-2xl font-bold mb-4">¡Gracias por registrarte!</h2>
            <p class="text-gray-700 mb-4">
                Antes de comenzar, por favor verifica tu correo electrónico haciendo clic en el enlace que te enviamos.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm text-green-600">
                    Hemos enviado un nuevo enlace de verificación a tu correo.
                </div>
            @endif

            <div class="mt-6 flex gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-xl hover:bg-pink-700">
                        Reenviar correo de verificación
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
