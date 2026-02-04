@extends('layouts.app')

@section('content')





<div class="container mx-auto px-4 py-6 max-w-md">

    <!-- SECCIÓN PÚBLICA -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white text-left mb-2">{{ $girl->name_artist ?? $girl->name }}</h1>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- FOTO PÚBLICA -->
            @if($girl->photo_public)
                <img src="{{ Storage::url($girl->photo_public) }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">Sin foto pública</span>
                </div>
            @endif

            <!-- DESCRIPCIÓN PÚBLICA -->
            <div class="p-4">
                
                <p class="text-gray-700">{{ $girl->description_public ?? 'Sin descripción pública' }}</p>
            </div>
        </div>
    </div>

    <!-- SECCIÓN PRIVADA -->
    <div class="mb-8">
        <div class="bg-gray-900 text-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-bold text-yellow-400 text-center">Contenido</h2>
            </div>
            <!-- DESCRIPCIÓN PRIVADA -->
<div class="p-4 border-t border-gray-700">
    <h3 class="text-lg font-semibold mb-1 text-pink-400">Descripción privada</h3>
    <p class="text-gray-200 whitespace-pre-line break-words">
        {{ $girl->description_private ?? 'No hay descripción privada' }}
    </p>
</div>

 <!-- CONTACTO PRIVADO -->
            <div class="p-4 border-t border-gray-700">
                <h3 class="text-lg font-semibold mb-1 text-green-400">Contacto</h3>
                <p class="text-gray-200 break-words">{{ $girl->contacto ?? 'No disponible' }}</p>
            </div>

            <!-- GRID DE FOTOS PRIVADAS -->
            <div class="grid grid-cols-2 gap-2 p-4">
                @for($i = 1; $i <= 6; $i++)
                    @if($girl->{"photo_private_$i"})
                        <img src="{{ asset('storage/' . $girl->{"photo_private_$i"}) }}"
                             class="w-full h-32 object-cover rounded-xl shadow-md">
                    @endif
                @endfor
            </div>

            <!-- VIDEO PRIVADO -->
            @if($girl->video_private)
                <div class="px-4 pb-4">
                    <video class="w-full rounded-xl shadow-md" controls>
                        <source src="{{ asset('storage/' . $girl->video_private) }}">
                        Tu navegador no soporta video.
                    </video>
                </div>
            @endif

           
        </div>
    </div>

</div>
@endsection
