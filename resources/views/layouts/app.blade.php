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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
   <body class="font-sans antialiased">
    <x-banner />

    @php
        $themeBg = 'bg-gray-100'; // Default gray background
        $bannerColor = 'bg-gray-800'; // Default dark banner

        if (auth()->check()) {
            if (auth()->user()->role == 'yardstaff') {
                $themeBg = 'bg-green-50';
                $bannerColor = 'bg-green-600';
            } elseif (auth()->user()->role == 'supervisor') {
                $themeBg = 'bg-blue-50';
                $bannerColor = 'bg-blue-600';
            } elseif (auth()->user()->role == 'technician') {
                $themeBg = 'bg-purple-50';
                $bannerColor = 'bg-purple-600';
            }
        }
    @endphp

    <div class="h-2 w-full {{ $bannerColor }}"></div>

    <div class="min-h-screen {{ $themeBg }}">

        @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
