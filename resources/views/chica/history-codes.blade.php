@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-200">
            <h1 class="text-2xl font-bold mb-6">
                📊 Historial semanal de códigos usados
            </h1>

            @php
                use Carbon\Carbon;
                $lastWeek = null;
                $weeklyCount = 0;
            @endphp

            @forelse ($history as $item)

                @php
                    $currentWeek = $item->used_at->format('o-W');
                    $weekStart = $item->used_at->copy()->startOfWeek(Carbon::MONDAY);
                    $weekEnd   = $item->used_at->copy()->endOfWeek(Carbon::SUNDAY);
                @endphp

                {{-- CAMBIO DE SEMANA --}}
                @if($lastWeek !== null && $lastWeek !== $currentWeek)
                    <div class="bg-gray-100 rounded-xl p-4 mb-6">
                        <p class="font-bold">
                            Total usados esta semana: {{ $weeklyCount }}
                        </p>
                    </div>

                    @php
                        $weeklyCount = 0;
                    @endphp
                @endif

                {{-- ENCABEZADO DE SEMANA --}}
                @if($lastWeek !== $currentWeek)
                    <div class="bg-black text-white rounded-xl px-4 py-2 mb-4">
                        📅 Semana del {{ $weekStart->format('d/m/Y') }}
                        al {{ $weekEnd->format('d/m/Y') }}
                    </div>

                    @php
                        $lastWeek = $currentWeek;
                    @endphp
                @endif

                {{-- ITEM --}}
                <div class="flex justify-between border-b py-2 text-sm">
                    <span class="font-mono font-bold">
                        {{ $item->code }}
                    </span>
                    <span class="text-gray-600">
                        {{ $item->used_at->format('d/m/Y H:i') }}
                    </span>
                </div>

                @php
                    $weeklyCount++;
                @endphp

            @empty
                <p class="text-gray-500">
                    No hay códigos usados aún.
                </p>
            @endforelse

            {{-- ÚLTIMA SEMANA --}}
            @if($weeklyCount > 0)
                <div class="bg-gray-100 rounded-xl p-4 mt-6">
                    <p class="font-bold">
                        Total usados esta semana: {{ $weeklyCount }}
                    </p>
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('girl.dashboard') }}"
                   class="inline-block bg-gray-700 text-white px-4 py-2 rounded-xl">
                    ← Volver al panel
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
