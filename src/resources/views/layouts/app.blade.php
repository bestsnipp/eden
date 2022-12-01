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
    <style type="text/css">
        [x-cloak] { display: none !important; }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.1/dist/trix.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/themes/nano.min.css">

    @livewireStyles
    @stack('css')

    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased" x-data x-eden-nice-scroll>

<div class="min-h-screen bg-slate-100 text-slate-500 flex w-full">
    <section x-data x-eden-nice-scroll
        data-dusk="sidebar"
        class="py-3 px-3 bg-white min-w-[240px] w-64 shadow-md sticky top-0 h-screen">
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
@foreach(\Dgharami\Eden\Facades\EdenModal::modals() as $component)
    @livewire($component->component, $component->params)
@endforeach

@stack('js')

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/trix@2.0.1/dist/trix.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.2.0/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.nicescroll@3.7.6/dist/jquery.nicescroll.min.js"></script>

@include('eden::widgets.toasts')

@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
