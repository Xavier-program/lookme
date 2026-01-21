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
                    <a href="{{ route('user.girls.show', $girl->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded">
                        Ver perfil completo
                    </a>

                    <a href="{{ route('user.buy.code', $girl->id) }}" class="px-4 py-2 bg-green-500 text-white rounded">
                        Comprar código
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
