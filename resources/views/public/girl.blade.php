<x-guest-layout>
    <div class="max-w-xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $girl->name_artist }}</h1>

        <img src="{{ asset('storage/' . $girl->photo_public) }}"
             class="w-full h-auto blur-md"
             alt="Foto pública">

        <div class="mt-4">
            <a href="#" class="btn btn-primary">Ver contenido</a>
        </div>
    </div>
</x-guest-layout>
