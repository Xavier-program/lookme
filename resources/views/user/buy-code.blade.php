@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Comprar código</h1>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="mb-4">
            Estás comprando el código para: <strong>{{ $girl->name_artist ?? $girl->name }}</strong>
        </p>

        <form action="{{ route('user.buy.code', $girl->id) }}" method="POST">
            @csrf

            <label class="block mb-2">Cantidad de códigos:</label>
            <input type="number" name="amount" class="border p-2 rounded w-full mb-4" value="1" min="1">

            <button class="px-4 py-2 bg-green-500 text-white rounded">
                Comprar
            </button>
        </form>
    </div>
</div>
@endsection
