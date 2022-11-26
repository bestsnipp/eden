<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />

    <style type="text/css">
        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
    @stack('css')

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

<div class="min-h-screen bg-slate-100 text-slate-500 flex w-full">
    <section data-dusk="sidebar" class="py-3 px-3 bg-white min-w-[240px] w-64 shadow-md">
        <div data-dusk="branding" class="mb-6">
            @include('eden::widgets.logo')
        </div>

        @include('eden::menu.index')
    </section>

    <!-- Page Content -->
    <main class="grow">

        <!-- Page Heading -->
        <header data-dusk="header" class="bg-white shadow-md mx-4 mt-4 rounded-md flex items-center py-4 px-4 sm:px-6">
            <div class="grow">
                @yield('header')
            </div>
            <div class="flex items-center gap-6">
                @include('eden::widgets.header-right')
            </div>
        </header>

        <div data-dusk="container" class="p-4">
            @yield('content')
        </div>

    </main>
</div>
{{--@include('eden::components.renderer', ['items' => \Dgharami\Eden\Eden::modals()])--}}

@livewireScripts
@stack('js')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.2.0/dist/cdn.min.js"></script>

@include('eden::widgets.toasts')
</body>
</html>
