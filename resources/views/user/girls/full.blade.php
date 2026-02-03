@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">
        {{ $girl->name_artist ?? $girl->name }}
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- FOTO PUBLICA -->
        <div class="border rounded-lg shadow p-4">
            @if($girl->photo_public)
                <img src="{{ Storage::url($girl->photo_public) }}" class="w-full h-64 object-cover rounded-lg">
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    Sin foto pública
                </div>
            @endif
        </div>

        <!-- DESCRIPCION -->
        <div class="border rounded-lgshadow p-4">
            <h2 class="text-xl font-bold mb-2">Descripció</h2>
            <p class="text-gray-700">
                {{ $girl->description_public ?? 'Sin descripción aún' }}
            </p>

            <div class="mt-6">
                <h2 class="text-xl font-bold mb-2">Contacto</h2>
                <p class="text-gray-700">
                    WhatsApp: <strong>{{ $girl->whatsapp ?? 'No disponible' }}</strong>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
