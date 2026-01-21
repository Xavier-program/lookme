@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Contenido privado</h1>

    <div class="grid grid-cols-3 gap-4">
        @for($i = 1; $i <= 6; $i++)
            @if($girl->{"photo_private_$i"})
                <img src="{{ asset('storage/' . $girl->{"photo_private_$i"}) }}"
                     class="w-full h-32 object-cover rounded-lg">
            @endif
        @endfor

        @if($girl->video_private)
            <video class="w-full rounded-lg" controls>
                <source src="{{ asset('storage/' . $girl->video_private) }}">
            </video>
        @endif
    </div>
</div>
@endsection
