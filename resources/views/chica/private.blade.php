@extends('layouts.app')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Perfil Privado
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">




<p>ID chica: {{ $girl->id }}</p>


            <!-- FORMULARIO PRINCIPAL (GUARDAR) -->
            <form method="POST" action="{{ route('chica.update.private') }}" enctype="multipart/form-data">
                @csrf

                <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">

                    <div>
                        <label class="block font-bold">Descripción privada</label>
                        <textarea name="description_private" class="w-full border rounded-lg p-2" rows="4">{{ $user->description_private }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        @for ($i = 1; $i <= 6; $i++)
                            <div class="mb-4">
                                <label class="block font-bold">Foto privada {{ $i }}</label>
                                <input type="file" name="photo_private_{{ $i }}" class="w-full">
                            </div>

                            @if($user->{"photo_private_$i"})
                                <div class="relative mb-4">
                                    <img src="{{ asset('storage/' . $user->{"photo_private_$i"}) }}" class="w-full h-24 object-cover rounded-lg">

                                    <!-- BOTÓN ELIMINAR (NO FORM ANIDADO) -->
                                    <button type="button"
                                        onclick="deletePhoto({{ $i }})"
                                        class="absolute top-1 right-1 bg-red-600 text-white px-2 py-1 rounded-lg text-xs">
                                        X
                                    </button>
                                </div>
                            @endif

                        @endfor

                    </div>

                    <div>
                        <label class="block font-bold">Video privado (opcional)</label>
                        <input type="file" name="video_private" class="w-full">
                        @if($user->video_private)
    <div class="relative mt-4">
        <video class="w-full h-32 object-cover rounded-lg" controls>
            <source src="{{ asset('storage/' . $user->video_private) }}" type="video/mp4">
        </video>

        <button type="button"
            onclick="deleteVideo()"
            class="absolute top-1 right-1 bg-red-600 text-white px-2 py-1 rounded-lg text-xs">
            X
        </button>
    </div>
@endif

                    </div>


                    

                    <button class="w-full bg-red-600 text-white rounded-lg py-2 font-bold">
                        Guardar cambios
                    </button>

                </div>
            </form>

           <!-- FORMULARIO OCULTO PARA ELIMINAR -->
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deletePhoto(index) {
        const form = document.getElementById('deleteForm');

        // CAMBIO AQUÍ: USAR route() DE LARAVEL
        form.action = "{{ url('/chica/privado/foto') }}/" + index;

        form.submit();
    }

    function deleteVideo() {
        const form = document.getElementById('deleteForm');
        form.action = "{{ route('chica.delete.video_private') }}";
        form.submit();
    }
</script>

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@endsection
