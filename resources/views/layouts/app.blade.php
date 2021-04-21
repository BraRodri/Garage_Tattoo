<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title> GARAGETATTOO Venta de Insumos para Tatuajes | @yield('pagina') </title>
        <x-meta></x-meta>
        <x-css></x-css>
    </head>
    <body>
        <x-header></x-header>

        {{ $slot }}

        <x-footer></x-footer>

        <x-js>
            {{$js}}
        </x-js>
    </body>
</html>
