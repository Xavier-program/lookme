@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Chicas disponibles</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($girls as $girl)
                <div class="bg-black/80 border border-red-600 rounded-xl shadow-xl shadow p-4">
                    <div class="h-48 w-full overflow-hidden rounded-lg">
                        @if($girl->photo_public)
                            <img src="{{ Storage::url($girl->photo_public) }}" alt="Foto pública" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                <span class="text-gray-400">Sin foto pública</span>
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-semibold mt-4">
                        {{ $girl->name_artist ?? $girl->name }}
                    </h2>

                    @if(isset($accessTimes[$girl->id]))
                        <div class="mt-2 bg-green-900 text-green-300 text-sm rounded-lg px-3 py-2 text-center"
                             data-countdown="{{ $accessTimes[$girl->id] }}"
                             id="countdown-{{ $girl->id }}">
                            ⏳ Tiempo restante: <span class="time">--:--</span>
                        </div>
                    @endif

                    <div class="mt-4 flex gap-2">
                        @if(isset($hasAccess[$girl->id]) && $hasAccess[$girl->id])
                            <a href="{{ url('girls/'.$girl->id.'/full') }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded">
                                Ver perfil completo
                            </a>
                        @else
                            <button onclick="openCodeModal({{ $girl->id }})"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg">
                                Ver perfil completo
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- BOTÓN FIJO -->
<div class="fixed bottom-4 left-0 w-full flex justify-center z-50">
    <button onclick="openBuyModal()"
        class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-black rounded-full shadow-2xl font-bold border-2 border-yellow-400 animate-pulse">
        Comprar códigos 🔓
    </button>
</div>


<!-- MODAL COMPRAR CÓDIGOS -->
<div id="buyModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-6 relative border border-gray-800">
        <button onclick="closeBuyModal()"
            class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center text-white">
            Comprar códigos de acceso 🔐
        </h2>

        <p class="text-gray-300 text-sm mb-4 text-center">
            Un código desbloquea el contenido privado de <strong>una sola chica</strong>
            durante <strong>1 hora</strong>.
        </p>

        <div class="space-y-3 mb-6">
            <div class="border rounded-lg p-3 flex justify-between border-gray-800">
                <span>1 código</span>
                <strong>$90 MXN</strong>
            </div>
            <div class="border rounded-lg p-3 flex justify-between border-gray-800">
                <span>5 códigos</span>
                <strong>$425 MXN</strong>
            </div>
            <div class="border rounded-lg p-3 flex justify-between border-gray-800">
                <span>10 códigos</span>
                <strong>$800 MXN</strong>
            </div>
            <div class="border rounded-lg p-3 flex justify-between bg-green-950 border-green-800">
                <span>15 códigos</span>
                <strong>$1,125 MXN</strong>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-4 text-sm mb-4 border border-gray-800">
            <p class="mb-2">
                📲 Para comprar, envía un WhatsApp a:
            </p>
            <p class="font-bold text-center text-lg">
                +52 55 1234 5678
            </p>
            <p class="mt-2 text-gray-400 text-center">
                Indica cuántos códigos deseas y el paquete.
            </p>
        </div>

        <p class="text-xs text-gray-500 text-center">
            * Los códigos son de uso único y expiran después de 1 hora.
        </p>
    </div>
</div>

<!-- MODAL CÓDIGO -->
<div id="codeModal" class="fixed inset-0 bg-black/80 hidden z-40 flex items-center justify-center px-4">
    <div class="bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-6 relative border border-gray-800">
        <button onclick="closeCodeModal()"
            class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center text-white">
            Ingresa tu código 🔐
        </h2>

        <p class="text-gray-300 text-sm mb-4 text-center">
            Introduce el código para ver el perfil completo.
        </p>

        <input id="codeInput" type="text" placeholder="Código"
            class="w-full border rounded-lg p-3 mb-4 bg-gray-800 text-white border-gray-700">

        <div class="flex justify-center">
            <button onclick="checkCode()"
                class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold">
                Verificar
            </button>
        </div>

        <p id="codeError" class="text-red-500 text-sm mt-3 text-center hidden">
            Código inválido o expirado.
        </p>
    </div>
</div>

<!-- MODAL LEGAL -->
<div id="legalModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-gray-900 rounded-2xl shadow-xl max-w-lg w-full p-6 relative border border-gray-800">
        <button onclick="closeLegalModal()"
            class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center text-white">
            Aviso Legal 📌
        </h2>

        <p class="text-gray-300 text-sm mb-4">
            Al continuar aceptas que esta plataforma es solo un intermediario para contactar a la chica.
            No nos hacemos responsables de acuerdos, servicios o cualquier transacción entre usuarios.
            El contenido privado es únicamente para uso personal y queda prohibida su difusión.
        </p>

        <p class="text-gray-300 text-sm mb-4">
            Si estás de acuerdo, presiona <strong>Continuar</strong> para ver el perfil completo.
        </p>

        <div class="flex justify-center">
            <button id="legalContinueBtn"
                class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold">
                Continuar
            </button>
        </div>
    </div>
</div>

<script>
let selectedGirlId = null;

function openBuyModal() {
    document.getElementById('buyModal').classList.remove('hidden');
}
function closeBuyModal() {
    document.getElementById('buyModal').classList.add('hidden');
}

function openCodeModal(id) {
    selectedGirlId = id;
    document.getElementById('codeModal').classList.remove('hidden');
}

function closeCodeModal() {
    document.getElementById('codeModal').classList.add('hidden');
}

function openLegalModal() {
    document.getElementById('legalModal').classList.remove('hidden');
}

function closeLegalModal() {
    document.getElementById('legalModal').classList.add('hidden');
}

function checkCode() {
    const code = document.getElementById('codeInput').value;

    fetch(`/girls/${selectedGirlId}/check-code`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeCodeModal();
            openLegalModal();
        } else {
            document.getElementById('codeError').classList.remove('hidden');
        }
    })
}

document.getElementById('legalContinueBtn').addEventListener('click', function() {
    window.location.href = "{{ url('girls') }}/" + selectedGirlId + "/full";
});

function startCountdowns() {
    document.querySelectorAll('[data-countdown]').forEach(el => {
        let endTime = Number(el.dataset.countdown);

        if (endTime < 10000000000) {
            endTime = endTime * 1000;
        }

        function update() {
            const now = Date.now();
            const diff = endTime - now;

            if (diff <= 0) {
                el.remove();
                return;
            }

            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);

            el.querySelector('.time').textContent =
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        update();
        setInterval(update, 1000);
    });
}

startCountdowns();
</script>

@endsection
