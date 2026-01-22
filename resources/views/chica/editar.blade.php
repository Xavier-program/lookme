@extends('layouts.app')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Perfil Público
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('chica.update.public') }}" enctype="multipart/form-data">
                @csrf

                <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">

                    <div>
                        <label class="block font-bold">Nombre artístico</label>
                        <input type="text" name="name_artist" value="{{ $user->name_artist }}" class="w-full border rounded-lg p-2">
                    </div>

                    <div>
                        <label class="block font-bold">Foto pública</label>
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



                    <div>
                        <label class="block font-bold">Descripción pública</label>
                        <textarea name="description_public" class="w-full border rounded-lg p-2" rows="4">{{ $user->description_public }}</textarea>
                    </div>

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

@endsection
