<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg shadow-lg">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre real -->
                <div>
                    <x-input-label for="name" :value="__('Nombre real')" class="text-gray-200" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full bg-gray-700 border-gray-600 text-white"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" class="text-gray-200" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full bg-gray-700 border-gray-600 text-white"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
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

                <!-- Confirmar contraseña -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="text-gray-200" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full bg-gray-700 border-gray-600 text-white"
                        type="password"
                        name="password_confirmation"
                        required
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Términos -->
                <div class="mt-4">
                    <label class="flex items-start gap-3 text-gray-300">
                        <input
                            type="checkbox"
                            name="terms"
                            class="mt-1 rounded border-gray-600 bg-gray-700"
                        >
                        <span class="text-sm">
                            Acepto los
                            <a href="{{ route('terms') }}" class="underline hover:text-white">Términos y Condiciones</a>
                            y la
                            <a href="{{ route('privacy') }}" class="underline hover:text-white">Política de Privacidad</a>.
                        </span>
                    </label>
                    <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="underline text-sm text-gray-300 hover:text-white">
                        ¿Ya tienes cuenta?
                    </a>

                    <x-primary-button class="ml-4">
                        Registrarse
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
