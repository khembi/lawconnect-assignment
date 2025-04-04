<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">

    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <a href="{{ route('topics.index') }}" class="text-base/7 font-semibold text-indigo-600">{{ config('app.name', 'Laravel') }}</a>
                <p class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl lg:text-balance">How can we help you?</p>
                @livewire('search-topic')
                <p class="mt-4 text-gray-600">You can <a href="{{ route('topics.create') }}" class="text-indigo-600 hover:text-indigo-500">create a new topic</a>, to help us understand your problem better.</p>  
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
            @yield('content')
            </div>
        </div>
    </div>

    <footer class="bg-white mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>