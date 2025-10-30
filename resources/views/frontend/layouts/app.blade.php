<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Website'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" as="style" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" media="print" onload="this.media='all'" />

        <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style" />
        <link rel="icon" href="{{ asset('image/favicon.png') }}" type="image/png">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </head>
    <body class="min-h-dvh bg-neutral-50 text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-100">
        @include('frontend.partials.header')

        <main class="relative">
            @yield('banner')
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
                @yield('content')
            </div>
        </main>

        @include('frontend.partials.footer')

        @stack('scripts')
    </body>
</html>


