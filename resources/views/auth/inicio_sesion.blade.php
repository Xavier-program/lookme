<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg shadow-lg">
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-200" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full bg-gray-700 border-gray-600 text-white"
                        type="email"
                        name="email"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-200" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full bg-gray-700 border-gray-600 text-white"
                        type="password"
                        name="password"
                        required
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('register') }}" class="underline text-sm text-gray-300 hover:text-white">
                        ¿No tienes una cuenta? Crea una
                    </a>

                    <x-primary-button>
                        Iniciar sesión
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
