<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Token CSRF para proteger formularios contra ataques CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Título de la página (se usa el nombre de la app configurado en .env) -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Aquí se cargan los estilos y scripts compilados con Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased">
        <!-- Contenedor principal con altura mínima de pantalla y fondo gris -->
        <div class="min-h-screen bg-gray-900">
            <!-- Incluye el menú de navegación (archivo layouts/navigation.blade.php) -->
            @include('layouts.navigation')

            <!-- Encabezado de la página -->
            @isset($header)
                <!-- Si existe la sección header, se muestra -->
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <!-- Aquí se inyecta el contenido del header -->
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Contenido principal de la página -->
            <main>
                <!-- Aquí se inyecta el contenido principal de cada vista -->
                  @yield('content')

            </main>
        </div>
    </body>
</html>
