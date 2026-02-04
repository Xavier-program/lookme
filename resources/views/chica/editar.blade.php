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
                        

<p class="text-sm text-gray-600 mb-1">
     <strong>Pasos importantes para completar tu perfil:</strong><br><br>

    Es muy importante que indiques tu <strong>ciudad o zona</strong>, ya que esto ayuda a que los usuarios te encuentren más rápido  
    y sepan si estás cerca de ellos para poder contactarte con mayor facilidad 📍
</p>


                        <textarea
                            name="name_artist"
                            rows="3"
                            placeholder="Ingresa aqui la ciudad donde te encuentras"
                            class="w-full border rounded-lg p-3 resize-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                        >{{ $user->name_artist }}</textarea>
                    </div>

                    <div class="mt-4">
    <label class="block font-bold">Descripción pública</label>
    <p class="text-sm text-gray-600 mb-2">
    Usa este espacio para presentarte y despertar interés 💖  
    Una buena descripción hace que más personas quieran ver tu contenido privado.
    <br><br>
    <strong>Ejemplo:</strong><br>
    “Soy una chica carismática, discreta y muy divertida.  
    Me encanta consentir y crear experiencias únicas 😉”
</p>
    <textarea
        name="description_public"
        class="w-full border rounded-lg p-2 min-h-[140px]"
        rows="4"
        placeholder="Esta descripción será visible para todos los visitantes (antes de pagar)."
    >{{ $user->description_public }}</textarea>
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
