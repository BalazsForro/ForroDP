<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    @livewireStyles
</head>
<body>
@include('components.layouts.navbar')
<main @class([
    'container',
    'p-4' => !in_array(request()->route()->getName(), ['login', 'register'])
    ])>

    {{ $slot }}

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="mainToast" class="toast" role="alert"
            aria-live="assertive" aria-atomic="true"
        >
            <div class="toast-header">
                <img id="toastIcon" src="{{ asset('images/Icon.jpeg') }}" class="rounded me-2"
                    alt="App icon" width="16" height="16"
                >
                <strong class="me-auto">{{ config('app.name') }}</strong>

                <small id="toastTime" class="text-muted"></small>

                <button type="button" class="btn-close" data-bs-dismiss="toast"
                    aria-label="Close"
                ></button>
            </div>

            <div id="toastBody" class="toast-body"></div>
        </div>
    </div>
</main>

@livewireScripts
</body>
</html>
