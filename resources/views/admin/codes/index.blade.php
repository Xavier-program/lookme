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
            <a href="{{ route('admin.codes.export.excel') }}"
   class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-xl">
    ⬇ Exportar Excel
</a>

        </div>

        <div class="bg-white rounded-2xl shadow p-6">

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

                        @php
                            use Carbon\Carbon;

                            $lastWeek = null;
                            $lastGirlId = null;

                            // 👉 aquí guardamos el conteo semanal
                            $weeklyUsedByGirl = [];
                        @endphp

                        @foreach($codes as $code)

                            @php
                                $currentWeek = $code->created_at->format('o-W');
                                $weekStart = $code->created_at->copy()->startOfWeek(Carbon::MONDAY);
                                $weekEnd = $code->created_at->copy()->endOfWeek(Carbon::SUNDAY);
                            @endphp

                            {{-- NUEVA SEMANA --}}
                            @if($lastWeek !== null && $lastWeek !== $currentWeek)
                                {{-- RESUMEN SEMANAL --}}
                                <tr class="bg-blue-50">
                                    <td colspan="6" class="px-4 py-3 font-bold">
                                        📊 Resumen semanal
                                        <ul class="mt-2 ml-4 list-disc">
                                            @foreach($weeklyUsedByGirl as $girlName => $count)
                                                <li>{{ $girlName }}: {{ $count }} códigos usados</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>

                                @php
                                    // reset semanal
                                    $weeklyUsedByGirl = [];
                                    $lastGirlId = null;
                                @endphp
                            @endif

                            {{-- ENCABEZADO SEMANA --}}
                            @if($lastWeek !== $currentWeek)
                                <tr class="bg-black text-white">
                                    <td colspan="6" class="px-4 py-2 font-bold text-center">
                                        📅 Semana del {{ $weekStart->format('d/m/Y') }}
                                        al {{ $weekEnd->format('d/m/Y') }}
                                    </td>
                                </tr>

                                @php
                                    $lastWeek = $currentWeek;
                                @endphp
                            @endif

                            {{-- AGRUPACIÓN POR CHICA --}}
                            @if($lastGirlId !== $code->girl_id)
                                <tr class="bg-gray-200">
                                    <td colspan="6" class="px-4 py-2 font-bold">
                                        {{ $code->girl ? $code->girl->name : 'Sin chica asignada' }}
                                    </td>
                                </tr>
                                @php
                                    $lastGirlId = $code->girl_id;
                                @endphp
                            @endif

                            {{-- CONTADOR SEMANAL (SOLO USADOS) --}}
                            @if($code->used_at && $code->girl)
                                @php
                                    $girlName = $code->girl->name . ' (ID ' . $code->girl->id . ')';
                                    $weeklyUsedByGirl[$girlName] =
                                        ($weeklyUsedByGirl[$girlName] ?? 0) + 1;
                                @endphp
                            @endif

                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">

                                <td class="border px-4 py-2 font-mono font-bold">
                                    {{ $code->code }}
                                </td>

                                <td class="border px-4 py-2">
                                    @php
                                        $estado = 'Disponible';
                                        $color = 'text-green-600';

                                        if ($code->used_at) {
                                            if (now()->greaterThan($code->used_at->copy()->addHour())) {
                                                $estado = 'Expirado';
                                                $color = 'text-gray-600';
                                            } else {
                                                $estado = 'Usado';
                                                $color = 'text-red-600';
                                            }
                                        }

                                        if ($code->expires_at && $code->expires_at->isPast()) {
                                            $estado = 'Expirado';
                                            $color = 'text-gray-600';
                                        }
                                    @endphp

                                    <span class="{{ $color }} font-bold">{{ $estado }}</span>
                                </td>

                                <td class="border px-4 py-2">
                                    {{ $code->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="border px-4 py-2">
                                    {{ $code->used_at ? $code->used_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td class="border px-4 py-2">
                                    @if($code->girl)
                                        {{ $code->girl->name }} (ID: {{ $code->girl->id }})
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="border px-4 py-2">
                                    {{ $code->used_at ? $code->used_at->copy()->addHour()->format('d/m/Y H:i') : '-' }}
                                </td>

                            </tr>

                        @endforeach

                        {{-- ÚLTIMO RESUMEN --}}
                        @if(count($weeklyUsedByGirl))
                            <tr class="bg-blue-50">
                                <td colspan="6" class="px-4 py-3 font-bold">
                                    📊 Resumen semanal
                                    <ul class="mt-2 ml-4 list-disc">
                                        @foreach($weeklyUsedByGirl as $girlName => $count)
                                            <li>{{ $girlName }}: {{ $count }} códigos usados</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $codes->links() }}
            </div>

        </div>

    </div>
</div>
@endsection
