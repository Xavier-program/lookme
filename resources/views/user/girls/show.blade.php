@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="md:w-1/2">
                @if($girl->photo_public)
                    <img src="{{ Storage::url($girl->photo_public) }}" alt="Foto pública" class="w-full h-auto rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">Sin foto pública</span>
                    </div>
                @endif
            </div>

            <div class="md:w-1/2">
                <h1 class="text-3xl font-bold mb-4">
                    {{ $girl->name_artist ?? $girl->name }}
                </h1>

                <p class="text-gray-700 mb-4">
                    Aquí puedes agregar una breve descripción pública de la chica.
                </p>

                <div class="flex gap-2">
                    <a href="{{ route('user.girls.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Volver
                    </a>

                    <a href="{{ route('user.buy.code', $girl->id) }}" class="px-4 py-2 bg-green-500 text-white rounded">
                        Comprar código
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
