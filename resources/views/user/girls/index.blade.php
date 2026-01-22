@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Chicas disponibles</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($girls as $girl)
            <div class="border rounded-lg shadow p-4">
                <div class="h-48 w-full overflow-hidden rounded-lg">
                    @if($girl->photo_public)
                        <img src="{{ Storage::url($girl->photo_public) }}" alt="Foto pública" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Sin foto pública</span>
                        </div>
                    @endif
                </div>

                <h2 class="text-xl font-semibold mt-4">
                    {{ $girl->name_artist ?? $girl->name }}
                </h2>

                <div class="mt-4 flex gap-2">
                    <!-- BOTÓN QUE ABRE MODAL CÓDIGO -->
                    <button onclick="openCodeModal({{ $girl->id }})"
                        class="px-4 py-2 bg-blue-500 text-white rounded">
                        Ver perfil completo
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- BOTÓN FIJO -->
<div class="fixed bottom-4 left-0 w-full flex justify-center z-40">
    <button onclick="openBuyModal()"
        class="px-6 py-3 bg-green-500 text-white rounded-full shadow-lg font-semibold">
        Comprar códigos 🔓
    </button>
</div>

<!-- MODAL COMPRAR CÓDIGOS -->
<div id="buyModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative">
        <button onclick="closeBuyModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center">
            Comprar códigos de acceso 🔐
        </h2>

        <p class="text-gray-600 text-sm mb-4 text-center">
            Un código desbloquea el contenido privado de <strong>una sola chica</strong>
            durante <strong>1 hora</strong>.
        </p>

        <div class="space-y-3 mb-6">
            <div class="border rounded-lg p-3 flex justify-between">
                <span>1 código</span>
                <strong>$90 MXN</strong>
            </div>
            <div class="border rounded-lg p-3 flex justify-between">
                <span>5 códigos</span>
                <strong>$425 MXN</strong>
            </div>
            <div class="border rounded-lg p-3 flex justify-between">
                <span>10 códigos</span>
                <strong>$800 MXN</strong>
            </div>
            <div class="border rounded-lg p-3 flex justify-between bg-green-50">
                <span>15 códigos</span>
                <strong>$1,125 MXN</strong>
            </div>
        </div>

        <div class="bg-gray-100 rounded-lg p-4 text-sm mb-4">
            <p class="mb-2">
                📲 Para comprar, envía un WhatsApp a:
            </p>
            <p class="font-bold text-center text-lg">
                +52 55 1234 5678
            </p>
            <p class="mt-2 text-gray-600 text-center">
                Indica cuántos códigos deseas y el paquete.
            </p>
        </div>

        <p class="text-xs text-gray-500 text-center">
            * Los códigos son de uso único y expiran después de 1 hora.
        </p>
    </div>
</div>

<!-- MODAL CÓDIGO -->
<div id="codeModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative">
        <button onclick="closeCodeModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center">
            Ingresa tu código 🔐
        </h2>

        <p class="text-gray-600 text-sm mb-4 text-center">
            Introduce el código para ver el perfil completo.
        </p>

        <input id="codeInput" type="text" placeholder="Código"
            class="w-full border rounded-lg p-3 mb-4">

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
<div id="legalModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">

        <button onclick="closeLegalModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center">
            Aviso Legal 📌
        </h2>

        <p class="text-gray-600 text-sm mb-4">
            Al continuar aceptas que esta plataforma es solo un intermediario para contactar a la chica.
            No nos hacemos responsables de acuerdos, servicios o cualquier transacción entre usuarios.
            El contenido privado es únicamente para uso personal y queda prohibida su difusión.
        </p>

        <p class="text-gray-600 text-sm mb-4">
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

    // Aquí validamos el código usando AJAX
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

</script>

@endsection
