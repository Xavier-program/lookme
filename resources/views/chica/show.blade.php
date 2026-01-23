@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white py-10">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-black/80 shadow-xl rounded-lg p-6 space-y-6 border border-gray-800">

            <!-- FOTO PÚBLICA -->
            @if($girl->photo_public)
                <div>
                    <img src="{{ asset('storage/' . $girl->photo_public) }}" class="w-full h-96 object-cover rounded-lg">
                </div>
            @endif

            <!-- NOMBRE ARTÍSTICO -->
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $girl->name_artist }}</h2>
            </div>

            <!-- DESCRIPCIÓN PÚBLICA -->
            @if($girl->description_public)
                <div>
                    <h3 class="font-bold text-white">Descripción pública</h3>
                    <p class="text-gray-300">{{ $girl->description_public }}</p>
                </div>
            @endif

            <!-- DESCRIPCIÓN PRIVADA -->
            @if($girl->description_private)
                <div>
                    <h3 class="font-bold text-white">Descripción</h3>
                    <p class="text-gray-300">{{ $girl->description_private }}</p>
                </div>
            @endif

            <!-- CONTACTO -->
            @if($girl->contacto)
                <div>
                    <h3 class="font-bold text-white">Contacto</h3>
                    <p class="text-gray-300">{{ $girl->contacto }}</p>
                </div>
            @endif

            <!-- FOTOS PRIVADAS -->
            <div>
                <h3 class="font-bold mb-2 text-white">Fotos</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $photo = 'photo_private_' . $i;
                        @endphp

                        @if($girl->$photo)
                            <div>
                                <img src="{{ asset('storage/' . $girl->$photo) }}" class="w-full h-40 object-cover rounded-lg">
                            </div>
                        @endif
                    @endfor

                </div>
            </div>

            <!-- VIDEO PRIVADO -->
            @if($girl->video_private)
                <div>
                    <h3 class="font-bold text-white">Video</h3>
                    <video controls class="w-full rounded-lg">
                        <source src="{{ asset('storage/' . $girl->video_private) }}" type="video/mp4">
                    </video>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
