@extends('layouts.app')

@section('content')
    <div class="py-4 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- TITULO -->
            <div class="mb-3">
                <h1 class="text-2xl font-bold text-white">
                    Bienvenid@
                </h1>
                <p class="text-gray-300 mt-1">
                    Aquí puedes configurar tu perfil público, subir contenido privado y ver tu historial de codigos.
                </p>
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 gap-6">

                <!-- PERFIL PÚBLICO -->
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200 max-w-2xl mx-auto w-full">
                    <h3 class="font-bold text-lg mb-4">📸 Perfil público</h3>

                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-40 h-40 rounded-2xl overflow-hidden bg-gray-200">
                            @if(auth()->user()->photo_public)
                                <img src="{{ asset('storage/' . auth()->user()->photo_public) }}"
                                     class="w-full h-full object-cover" alt="Foto pública">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500">
                                    Sin foto pública
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <p class="text-gray-700">
                                <span class="font-semibold">Ciudad</span>
                                {{ auth()->user()->name_artist ?? 'Aún no definido' }}
                            </p>


                            @if(auth()->user()->description_public)
    <p class="text-gray-600 mt-2 whitespace-pre-line break-words">
        {{ auth()->user()->description_public }}
    </p>
@else
    <p class="text-gray-400 mt-2 italic">
        Sin descripción pública
    </p>
@endif


                            <p class="text-gray-500 mt-1">
                                Esto será lo que verán los visitantes antes de pagar.
                            </p>

                            <div class="mt-4">
                                <a href="{{ route('chica.edit.public') }}"
                                   class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl font-bold">
                                    Editar perfil público
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEPARADOR PRIVADO -->
                <div class="text-center text-gray-900">
                    <span class="inline-block px-4 py-2 bg-gray-100 rounded-full">
                        Todo lo siguiente es privado y se accede solo con un código
                    </span>
                </div>

                <!-- PERFIL PRIVADO -->
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200 max-w-2xl mx-auto w-full">
                    <h3 class="font-bold text-lg mb-2">🔒 Perfil privado</h3>
                    <p class="text-gray-500 mb-4">
                        Aquí puedes subir fotos privadas, un video y descripción.  
                        El usuario solo podrá verlo con un código.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <!-- FOTOS PRIVADAS -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <h4 class="font-bold mb-2">Fotos privadas</h4>
                            <p class="text-gray-500 text-sm mb-3">Mínimo 6 fotos.</p>
                            <div class="grid grid-cols-3 gap-2">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="h-24 w-full bg-gray-200 rounded-lg overflow-hidden">
                                        @if(auth()->user()->{"photo_private_$i"})
                                            <img src="{{ asset('storage/' . auth()->user()->{"photo_private_$i"}) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="Foto privada {{ $i }}">
                                        @else
                                            <div class="flex items-center justify-center h-full text-gray-500 text-xs">
                                                Foto {{ $i }}
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- VIDEO PRIVADO -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <h4 class="font-bold mb-2">Video privado (opcional)</h4>
                            @if(auth()->user()->video_private)
                                <video class="w-full rounded-lg" controls>
                                    <source src="{{ asset('storage/' . auth()->user()->video_private) }}" type="video/mp4">
                                </video>
                            @else
                                <p class="text-gray-500">Aún no hay video</p>
                            @endif
                        </div>

                        <!-- DESCRIPCION + CONTACTO + HISTORIAL -->
<div class="bg-gray-50 p-4 rounded-xl">
    <h4 class="font-bold mb-2">Descripción privada</h4>
    <p class="text-gray-600 mb-4 whitespace-pre-line break-words">
        {{ auth()->user()->description_private ?? 'No hay descripción privada aún' }}
    </p>

    <h4 class="font-bold mb-2">Contacto privado</h4>
    <p class="text-gray-600 mb-4 break-words">
        {{ auth()->user()->contacto ?? 'No hay contacto definido' }}
    </p>

    <!-- BOTÓN VER HISTORIAL -->
    <a href="{{ route('chica.history.codes') }}"
       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold">
       Ver historial de códigos
    </a>
</div>

                    </div>

                    <div class="mt-6">
                        <a href="{{ route('chica.edit.private') }}"
                           class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl font-bold">
                            Editar perfil privado
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
