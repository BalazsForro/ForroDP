<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body>
@include('components.layouts.navbar')
<main @class([
    'container',
    'p-4' => !in_array(request()->route()->getName(), ['login', 'register'])
    ])>
    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
