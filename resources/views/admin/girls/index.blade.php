@extends('layouts.app')

@section('content')
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-white">Panel de administración</h1>

    <div class="flex gap-3">
        <a href="{{ route('admin.codes.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-xl">
           Códigos
        </a>

        <button onclick="openGenerateModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-xl">
            Generar códigos
        </button>
    </div>
</div>
    


            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                @foreach($girls as $girl)
                    <div class="bg-white rounded-2xl shadow border overflow-hidden">

                        <!-- FOTO -->
                        <div class="h-56 bg-gray-200">
                            @if($girl->photo_public)
                                <img src="{{ asset('storage/' . $girl->photo_public) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="h-full flex items-center justify-center text-gray-500">
                                    Sin foto
                                </div>
                            @endif
                        </div>

                        <!-- INFO -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg">
                                {{ $girl->name_artist ?? $girl->name }}
                            </h3>

                            <div class="mt-4">
                                <a href="{{ route('admin.girls.show', $girl) }}"
                                   class="block text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl font-bold">
                                    Administrar
                                </a>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="mt-6 flex gap-3">

                            <!-- Volver -->
                            <a href="{{ route('user.girls.index') }}"
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold px-6 py-2 rounded-xl">
                                ← Volver
                            </a>

                            <!-- Eliminar -->
                            <form action="{{ route('admin.girls.destroy', $girl) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded-xl"
                                        onclick="return confirm('¿Seguro que quieres eliminar a esta chica? Esta acción no se puede deshacer.')">
                                    Eliminar chica
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach



                <!-- MODAL GENERAR CÓDIGOS -->
<div id="generateModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative">
        <button onclick="closeGenerateModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">✕</button>

        <h2 class="text-2xl font-bold mb-4 text-center">
            Generar códigos 🔐
        </h2>

        <form action="{{ route('admin.generate.codes') }}" method="POST">
            @csrf

            <label class="block mb-2">Cantidad de códigos:</label>
            <input type="number" name="amount" class="border p-2 rounded w-full mb-4" value="1" min="1">

            <p class="text-sm text-gray-600 mb-4">
                Precios:
                <br>1 código = $90
                <br>5 códigos = $425
                <br>10 códigos = $800
                <br>15 códigos = $1,125
            </p>

            <button class="px-4 py-2 bg-green-600 text-white rounded-xl w-full font-bold">
                Generar
            </button>
        </form>
    </div>
</div>

<script>
function openGenerateModal() {
    document.getElementById('generateModal').classList.remove('hidden');
}
function closeGenerateModal() {
    document.getElementById('generateModal').classList.add('hidden');
}
</script>


            </div>

        </div>
    </div>

    
@endsection
