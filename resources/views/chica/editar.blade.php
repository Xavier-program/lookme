@extends('layouts.app')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Perfil Público
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('chica.update.public') }}" enctype="multipart/form-data">
                @csrf

                <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">

                   <div>
                        <label class="block font-bold mb-1">Nombre artístico / Ciudad</label>

<p class="text-sm text-gray-600 mb-2">
     <strong>Pasos importantes para completar tu perfil:</strong><br><br>

    1. <strong>Ciudad donde te encuentras:</strong> Indica tu ciudad o zona de trabajo para que los usuarios puedan ubicarte fácilmente.<br>
    2. <strong>Nombre artístico:</strong> Ingresa el nombre con el que deseas presentarte en la plataforma; este será visible para los usuarios.<br>
    3. <strong>Breve descripción:</strong> Escribe una descripción clara y profesional que destaque tu estilo y atraiga a los usuarios a tu perfil.<br><br>

    Completar correctamente esta información es fundamental para mejorar tu visibilidad, generar mayor confianza y facilitar el contacto con los usuarios.
</p>


                        <textarea
                            name="name_artist"
                            rows="3"
                            placeholder="Ejemplo:  Chetumal · Soy Luna, una chica auténtica y encantadora.  Ven y conóceme, te garantizo una experiencia que no olvidarás."
                            class="w-full border rounded-lg p-3 resize-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                        >{{ $user->name_artist }}</textarea>
                    </div>



                    <div>
                        <label class="block font-bold">Foto pública</label>
                        <p class="text-sm text-gray-600 mt-1">
                        Tu foto pública es tu carta de presentación. Elige una imagen atractiva que despierte curiosidad y motive a los usuarios a entrar a tu perfil 💕
                    </p>

                        <input type="file" name="photo_public" class="w-full">
                    </div>


                    @if($user->photo_public)
    <div class="mb-4">
        <img src="{{ asset('storage/' . $user->photo_public) }}" class="w-32 h-32 object-cover rounded-lg">

        <!-- BOTÓN ELIMINAR (NO DENTRO DEL FORM PRINCIPAL) -->
        <button type="button"
            onclick="deletePhotoPublic()"
            class="mt-2 bg-red-600 text-white px-4 py-2 rounded-lg">
            Eliminar foto pública
        </button>
    </div>
@endif



                    

                   <button class="w-full bg-indigo-600 text-white rounded-lg py-2 font-bold">
    Guardar cambios
</button>


                </div>
            </form>

        </div>
    </div>




    <!-- FORMULARIO OCULTO PARA ELIMINAR FOTO PÚBLICA -->
<form id="deletePublicForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deletePhotoPublic() {
        const form = document.getElementById('deletePublicForm');
        form.action = "{{ route('chica.delete.photo_public') }}";
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
