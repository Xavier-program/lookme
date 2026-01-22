@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Códigos Generados</h1>

            <a href="{{ route('admin.dashboard') }}"
               class="bg-gray-700 hover:bg-gray-800 text-white font-bold px-6 py-2 rounded-xl">
                ← Volver
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">

            <!-- CONTENEDOR RESPONSIVO -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Código</th>
                            <th class="border px-4 py-2">Estado</th>
                            <th class="border px-4 py-2">Fecha de creación</th>
                            <th class="border px-4 py-2">Fecha de uso</th>
                            <th class="border px-4 py-2">Usado en</th>
                            <th class="border px-4 py-2">Vencimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($codes as $code)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">

                                <!-- CÓDIGO -->
                                <td class="border px-4 py-2 font-mono font-bold">
                                    {{ $code->code }}
                                </td>

                                <!-- ESTADO -->
                                <td class="border px-4 py-2">
                                    @php
                                        $estado = 'Disponible';
                                        $color = 'text-green-600';

                                        if ($code->used_at) {

                                            // Si ya pasó la hora
                                            if (now()->greaterThan($code->used_at->copy()->addHour())) {
                                                $estado = 'Expirado';
                                                $color = 'text-gray-600';
                                            } else {
                                                $estado = 'Usado';
                                                $color = 'text-red-600';
                                            }

                                        }

                                        // Si el código expiró por expires_at del sistema
                                        if ($code->expires_at && $code->expires_at->isPast()) {
                                            $estado = 'Expirado';
                                            $color = 'text-gray-600';
                                        }
                                    @endphp

                                    <span class="{{ $color }} font-bold">{{ $estado }}</span>
                                </td>

                                <!-- FECHA DE CREACIÓN -->
                                <td class="border px-4 py-2">
                                    {{ $code->created_at->format('d/m/Y H:i') }}
                                </td>

                                <!-- FECHA DE USO -->
                                <td class="border px-4 py-2">
                                    {{ $code->used_at ? $code->used_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                <!-- USADO EN -->
                                <td class="border px-4 py-2">
                                    @if($code->girl)
                                        {{ $code->girl->name }} (ID: {{ $code->girl->id }})
                                    @else
                                        —
                                    @endif
                                </td>

                                <!-- VENCIMIENTO -->
                                <td class="border px-4 py-2">
                                    {{ $code->used_at ? $code->used_at->copy()->addHour()->format('d/m/Y H:i') : '-' }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN -->
            <div class="mt-4">
                {{ $codes->links() }}
            </div>

        </div>

    </div>
</div>
@endsection
