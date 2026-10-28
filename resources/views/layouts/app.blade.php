<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/filament/my-scripts.js') }}"></script>
    </head>
    <header class="sec-header">
        <x-header />
    </header>
    <body> <!--class="font-sans antialiased"-->
        <div > <!--class="min-h-screen bg-gray-100 dark:bg-gray-900" -->           
            <main style="height: calc(97vh - 110px);">
                {{ $slot }}
            </main>
        </div>
    </body>
    
</html>
