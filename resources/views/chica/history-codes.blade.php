@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-200">
            <h1 class="text-2xl font-bold mb-4">Historial de códigos usados</h1>

            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Código</th>
                        <th class="border px-4 py-2">Fecha de uso</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->code }}</td>
                            <td class="border px-4 py-2">{{ $item->used_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="border px-4 py-2" colspan="2">No hay códigos usados aún</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                <a href="{{ route('girl.dashboard') }}"
                   class="inline-block bg-gray-700 text-white px-4 py-2 rounded-xl">
                    Volver al panel
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
