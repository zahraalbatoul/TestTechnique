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
        <style>
            /* subtle gradient header accent */
            header.bg-white { background-image: linear-gradient(to right, rgba(124,58,237,0.06), rgba(22,163,74,0.06)); }
            .dark header.bg-white { background-image: none; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="mt-12 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <p>© {{ date('Y') }} Multi‑Tenant PM. All rights reserved.</p>
                    <div class="flex items-center gap-3 mt-3 sm:mt-0">
                        <a href="{{ route('organizations.index') }}" class="link-action">Organizations</a>
                        <a href="{{ route('organizations.create') }}" class="link-action">Create</a>
                        <a href="/api/documentation" class="link-action">API Docs</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
