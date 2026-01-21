@extends('layouts.app')

@section('content')
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4">

            <h1 class="text-2xl font-bold text-white mb-6">
                Panel de administración 
            </h1>

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

            </div>

        </div>
    </div>
@endsection
