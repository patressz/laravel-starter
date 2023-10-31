<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Page Title</title>

        {{-- Styles --}}
        @vite(['resources/sass/admin/app.scss'])
        @stack('styles')
    </head>
    <body class="antialiased">
        @yield('content')

        {{-- Scripts --}}
        @vite(['resources/js/admin/app.js'])
        @stack('scripts')
    </body>
</html>
