<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ mix('css/app.css') }}?{{ time() }}">
        <script src="{{ mix('js/app.js') }}?{{ time() }}"></script>
        @stack('scripts')
    </head>
    <body class="font-sans">
        <div class="container-fluid">
            <div class="w-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
