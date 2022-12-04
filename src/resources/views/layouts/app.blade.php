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

    <!-- Assets - Styles -->
    @foreach(\Dgharami\Eden\Facades\EdenAssets::styles() as $style)
        <link rel="stylesheet" data-key="{{ $style['key'] }}" href="{{ $style['url'] }}" />
    @endforeach

    @livewireStyles
    @stack('css')

    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased" x-data x-eden-nice-scroll>

<div class="min-h-screen bg-slate-100 text-slate-500 flex w-full">
    <section
        x-data
        x-eden-nice-scroll
        data-dusk="sidebar"
        class="py-3 px-3 bg-white min-w-[240px] w-64 shadow-md sticky top-0 h-screen">
        <div data-dusk="branding" class="mb-6">
            {!! \Dgharami\Eden\Facades\Eden::getLogo() !!}
        </div>

        @include('eden::menu.index')
    </section>

    <!-- Page Content -->
    <main class="grow flex flex-col">

        <!-- Page Heading -->
        <header data-dusk="header" class="bg-white shadow-md mx-4 mt-4 rounded-md flex items-center py-4 px-4 sm:px-6">
            <div class="grow">
                @yield('header')
            </div>
            <div class="flex items-center gap-6">
                @include('eden::widgets.header-right')
            </div>
        </header>

        <div data-dusk="container" class="px-4 py-4 grow">
            @yield('content')
        </div>

        <footer class="px-4">
            {!! \Dgharami\Eden\Facades\Eden::getFooter() !!}
        </footer>

    </main>
</div>
@foreach(\Dgharami\Eden\Facades\EdenModal::modals() as $component)
    @livewire($component->component, $component->params)
@endforeach

@stack('js')

<!-- Assets - Scripts -->
@foreach(\Dgharami\Eden\Facades\EdenAssets::scripts() as $script)
<script data-key="{{ $script['key'] }}" src="{{ $script['url'] }}"></script>
@endforeach

@include('eden::widgets.toasts')

@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
