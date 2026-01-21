@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Acceso privado</h1>

    <form action="{{ route('user.girls.private', $girl->id) }}" method="POST">
        @csrf

        <label class="block mb-2">Ingresa tu código:</label>
        <input type="text" name="code" class="border p-2 rounded w-full mb-4">

        <button class="px-4 py-2 bg-blue-500 text-white rounded">
            Ver contenido
        </button>
    </form>

    <!-- BOTÓN COMPRAR CÓDIGO -->
    <div class="mt-4">
       <form action="{{ route('user.buy.code', $girl->id) }}" method="POST">
    @csrf
    <button class="px-4 py-2 bg-green-500 text-white rounded">
        Comprar código
    </button>
</form>

    </div>
</div>
@endsection
