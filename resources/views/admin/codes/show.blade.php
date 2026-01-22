@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6 text-white">
        Códigos generados - Batch #{{ $batch->id }}
    </h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Código</th>
                    <th class="px-4 py-2">Generado</th>
                    <th class="px-4 py-2">Expira</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch->codes as $code)
                    <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }}">
                        <td class="border px-4 py-2 font-mono">{{ $code->code }}</td>
                        <td class="border px-4 py-2">{{ $code->created_at }}</td>
                        <td class="border px-4 py-2">{{ $code->expires_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>    
    </div>

</div>
@endsection
