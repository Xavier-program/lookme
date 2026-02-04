<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-black ">
        <div class="max-w-3xl w-full bg-black  rounded-2xl p-8 -mt-28">

            <!-- LOGO ARRIBA -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="LookMe Logo" class="h-60 w-auto">
            </div>

            <h1 class="text-3xl font-bold text-white mb-4 text-center">Bienvenido</h1>

            <p class="text-gray-300 mb-4">
                LookMe te permite explorar perfiles reales, conocer acompañantes disponibles
                y descubrir lo que las hace únicas antes de contactarlas.
            </p>

            <p class="text-gray-300 mb-6">
                El contenido privado está disponible solo mediante acceso,
                asegurando discreción, respeto y evitando el uso indebido del material.
            </p>
            <!-- SOLO BOTÓN CONTINUAR -->
            <div class="flex justify-center">
                <button
    onclick="openModal()"
    class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold">
    Continuar
</button>
            </div>

<!-- MODAL AVISO LEGAL -->
<div id="legalModal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">
    
    <div class="bg-gray-900 rounded-2xl max-w-md w-full p-6 mx-4 shadow-xl">
        
        <h3 class="text-xl font-bold text-white mb-3 text-center">
            Aviso importante
        </h3>

        <p class="text-gray-300 text-sm mb-3">
            LookMe es una plataforma digital destinada exclusivamente a personas
            <strong>mayores de 18 años</strong>.
        </p>

        <p class="text-gray-300 text-sm mb-3">
            El contenido mostrado dentro de la plataforma es responsabilidad
            de cada perfil. LookMe <strong>no ofrece, promueve ni intermedia
            servicios presenciales</strong> ni acuerdos fuera de la plataforma.
        </p>

        <p class="text-gray-300 text-sm mb-4">
            Cualquier contacto o comunicación realizada fuera de la plataforma
            es responsabilidad exclusiva de los usuarios.
        </p>

        <p class="text-gray-400 text-xs text-center mb-5">
            Al continuar confirmas que eres mayor de edad y aceptas usar
            LookMe bajo tu propia responsabilidad.
        </p>

        <div class="flex gap-3">
            <button onclick="closeModal()"
                    class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-xl">
                Cancelar
            </button>

            <a href="{{ route('user.girls.index') }}"
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl text-center font-bold">
                Aceptar y continuar
            </a>
        </div>
    </div>
</div>


            <!-- TEXTO INICIAR SESIÓN ABAJO -->
            <div class="mt-8 text-gray-400 text-sm text-center">
                <button onclick="openLoginModal()" class="underline text-gray-400">
    Iniciar sesión
</button>
            </div>

        </div>
    </div>

<script>
    function openModal() {
        document.getElementById('legalModal').classList.remove('hidden');
        document.getElementById('legalModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('legalModal').classList.add('hidden');
        document.getElementById('legalModal').classList.remove('flex');
    }
</script>

<!-- MODAL AVISO LEGAL LOGIN / REGISTRO -->
<div id="loginLegalModal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">

    <div class="bg-gray-900 rounded-2xl max-w-md w-full p-6 mx-4 shadow-xl">

        <h3 class="text-xl font-bold text-white mb-3 text-center">
            Aviso antes de continuar
        </h3>

        <p class="text-gray-300 text-sm mb-3">
            El registro e inicio de sesión en LookMe está permitido únicamente
            para personas <strong>mayores de 18 años</strong>.
        </p>

        <p class="text-gray-300 text-sm mb-3">
            Al crear una cuenta, confirmas que la información proporcionada es
            verídica y que utilizarás la plataforma de forma responsable.
        </p>

        <p class="text-gray-300 text-sm mb-4">
            LookMe no se hace responsable de interacciones, acuerdos o contactos
            realizados fuera de la plataforma. Cada usuario actúa bajo su
            propia responsabilidad.
        </p>

        <p class="text-gray-400 text-xs text-center mb-5">
            Al continuar aceptas estos términos y confirmas que eres mayor de edad.
        </p>

        <div class="flex gap-3">
            <button onclick="closeLoginModal()"
                    class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-xl">
                Cancelar
            </button>

            <a href="{{ route('force.login') }}"
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl text-center font-bold">
                Aceptar y continuar
            </a>
        </div>

    </div>
</div>

<script>
    function openLoginModal() {
        const modal = document.getElementById('loginLegalModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeLoginModal() {
        const modal = document.getElementById('loginLegalModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>


</x-guest-layout>
