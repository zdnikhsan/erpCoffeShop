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
        @stack('styles')
    </head>
    <body class="font-sans antialiased text-charcoal bg-offwhite">
        <div x-data="{ mobileSidebarOpen: false }" class="min-h-screen flex overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-y-auto lg:pl-64">
                <!-- Topbar -->
                @include('layouts.topbar')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-gray-200/50 py-5">
                        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:pl-12 lg:pr-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 py-8">
                    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:pl-12 lg:pr-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
