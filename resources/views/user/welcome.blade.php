<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-black ">
        <div class="max-w-3xl w-full bg-black  rounded-2xl p-8 -mt-28">

            <!-- LOGO ARRIBA -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="LookMe Logo" class="h-60 w-auto">
            </div>

            <h1 class="text-3xl font-bold text-white mb-4 text-center">Bienvenido</h1>

            <p class="text-gray-300 mb-4">
                LookMe es una plataforma que conecta a personas con acompañantes de forma segura y respetuosa.
                Aquí podrás ver perfiles públicos, precios y servicios disponibles.
            </p>

            <p class="text-gray-300 mb-6">
                Para ver contenido privado (fotos o videos), se requiere una pequeña cuota de acceso.
                Esto protege la privacidad de las chicas y evita que usuarios solo busquen contenido sin pagar.
            </p>

            <!-- SOLO BOTÓN CONTINUAR -->
            <div class="flex justify-center">
                <a href="{{ route('user.girls.index') }}"
                   class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold">
                    Continuar
                </a>
            </div>

            <!-- TEXTO INICIAR SESIÓN ABAJO -->
            <div class="mt-8 text-gray-400 text-sm text-center">
                <a href="{{ route('force.login') }}" class="underline text-gray-400">
                    Iniciar sesión
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
