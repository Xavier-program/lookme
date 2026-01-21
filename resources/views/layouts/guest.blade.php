<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Token CSRF para proteger formularios contra ataques CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Título de la página (usa el nombre configurado en .env) -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Carga de estilos y scripts compilados con Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased">
        <!-- Contenedor principal centrado verticalmente con fondo gris -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <!-- Logo de la aplicación (componente Blade) -->
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <!-- Contenedor del contenido (tarjeta blanca con sombra) -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <!-- Aquí se inyecta el contenido principal de la vista -->
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
