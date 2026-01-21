@extends('layouts.app')

@section('content')
    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4">

            <a href="{{ route('admin.girls.index') }}"
               class="text-gray-300 hover:text-white mb-4 inline-block">
                ← Volver
            </a>

            <h1 class="text-2xl font-bold text-white mb-6">
                Administrando: {{ $user->name_artist ?? $user->name }}
            </h1>

            <!-- PERFIL PÚBLICO -->
            <div class="bg-white rounded-2xl p-6 mb-6">
                <h3 class="font-bold mb-3">Perfil público</h3>

                @if($user->photo_public)
                    <img src="{{ asset('storage/' . $user->photo_public) }}"
                         class="w-40 h-40 object-cover rounded-xl mb-4">
                @endif

                <p><strong>Nombre:</strong> {{ $user->name_artist }}</p>
                <p class="mt-2">{{ $user->description_public }}</p>
            </div>

            <!-- PERFIL PRIVADO -->
            <div class="bg-white rounded-2xl p-6 mb-6">
                <h3 class="font-bold mb-3">Fotos privadas</h3>

                <div class="grid grid-cols-3 gap-3">
                    @for($i = 1; $i <= 6; $i++)
                        @if($user->{"photo_private_$i"})
                            <img src="{{ asset('storage/' . $user->{"photo_private_$i"}) }}"
                                 class="w-full h-32 object-cover rounded-lg">
                        @endif
                    @endfor
                </div>
            </div>

            <!-- VIDEO -->
            <div class="bg-white rounded-2xl p-6 mb-6">
                <h3 class="font-bold mb-3">Video privado</h3>

                @if($user->video_private)
                    <video class="w-full rounded-lg" controls>
                        <source src="{{ asset('storage/' . $user->video_private) }}">
                    </video>
                @else
                    <p>No hay video</p>
                @endif
            </div>

            <!-- DESCRIPCIÓN PRIVADA -->
            <div class="bg-white rounded-2xl p-6">
                <h3 class="font-bold mb-3">Descripción privada</h3>
                <p class="whitespace-pre-line break-words">
                    {{ $user->description_private }}
                </p>
            </div>



 @if($user->photo_public)
    <div class="relative inline-block">
        <img src="{{ asset('storage/' . $user->photo_public) }}"
             class="w-40 h-40 object-cover rounded-xl mb-4">

        <form action="{{ route('admin.girls.delete.photo_public', $user) }}" method="POST"
              class="absolute top-1 right-1">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center">
                ✕
            </button>
        </form>
    </div>
@endif


@for($i = 1; $i <= 6; $i++)
    @if($user->{"photo_private_$i"})
        <div class="relative">
            <img src="{{ asset('storage/' . $user->{"photo_private_$i"}) }}"
                 class="w-full h-32 object-cover rounded-lg">

            <form action="{{ route('admin.girls.delete.photo_private', [$user, $i]) }}"
                  method="POST"
                  class="absolute top-1 right-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center">
                    ✕
                </button>
            </form>
        </div>
    @endif
@endfor


@if($user->video_private)
    <div class="relative">
        <video class="w-full rounded-lg" controls>
            <source src="{{ asset('storage/' . $user->video_private) }}">
        </video>

        <form action="{{ route('admin.girls.delete.video_private', $user) }}" method="POST"
              class="absolute top-2 right-2">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center">
                ✕
            </button>
        </form>
    </div>
@endif


@if($user->description_public)      
    <form action="{{ route('admin.girls.delete.description_public', $user) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="bg-red-600 text-white px-4 py-2 rounded-xl">
            Eliminar descripción pública
        </button>
    </form>
@endif


@if($user->description_private)
    <form action="{{ route('admin.girls.delete.description_private', $user) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="bg-red-600 text-white px-4 py-2 rounded-xl">
            Eliminar descripción privada
        </button>
    </form>
@endif




        </div>
    </div>
@endsection
