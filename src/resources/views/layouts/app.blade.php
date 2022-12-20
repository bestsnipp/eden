<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Dark Mode -->
    <script>
        let switchColorScheme = function (theme) {
            if (undefined !== theme) {
                switch (theme.toString().toLowerCase()) {
                    case 'light':
                        localStorage.edenTheme = 'light';
                        break;
                    case 'dark':
                        localStorage.edenTheme = 'dark';
                        break;
                    case 'system':
                        localStorage.removeItem('edenTheme');
                        break;
                    default:
                        // Nothing to do
                }
            }

            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.edenTheme === 'dark' || (!('edenTheme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        };
        // Check Initial Color Scheme
        (switchColorScheme)()
    </script>

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

    @livewireStyles

    <!-- Assets - Styles -->
    @foreach(\BestSnipp\Eden\Facades\EdenAssets::styles() as $style)
        <link rel="stylesheet" data-key="{{ $style['key'] }}" href="{{ $style['url'] }}" />
    @endforeach

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ mix('/css/eden.css', 'vendor/eden') }}" />
    {!! \BestSnipp\Eden\Facades\EdenAssets::generateBrandColors() !!}

    @stack('css')
</head>
<body class="font-sans antialiased" x-data x-eden-nice-scroll>

<div class="min-h-screen bg-slate-100 text-slate-500 flex w-full dark:bg-slate-800 dark:text-slate-400">
    <!-- Sidebar -->
    <div class="fixed inset-0 bg-black/50 z-[49] hidden" x-ref="sidebarBackdrop"
         x-on:click.prevent="$($refs.sidebar).toggle(); $($refs.sidebarBackdrop).toggle()"></div>
    <section
        x-eden-nice-scroll
        x-ref="sidebar"
        data-dusk="sidebar"
        class="py-3 px-3 bg-white w-64 shadow-md fixed top-0 h-screen z-50 hidden lg:block dark:bg-slate-700 dark:text-white">
        <div data-dusk="branding" class="mb-6">
            {!! \BestSnipp\Eden\Facades\Eden::getLogo() !!}
        </div>

        @include('eden::menu.index')
    </section>

    <!-- Page Content -->
    <main class="grow flex flex-col lg:ml-64 transition-[margin]">

        <!-- Page Heading -->
        <header data-dusk="header" class="bg-white shadow-md mx-4 mt-4 rounded-md flex items-center gap-4 py-4 px-4 sm:px-6 dark:bg-slate-700 dark:shadow-slate-900 dark:text-white">
            <button type="button" class="lg:hidden" x-on:click.prevent="$($refs.sidebar).toggle(); $($refs.sidebarBackdrop).toggle()">{!! edenIcon('menu-alt-1') !!}</button>
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
            {!! \BestSnipp\Eden\Facades\Eden::getFooter() !!}
        </footer>

    </main>
</div>
@foreach(\BestSnipp\Eden\Facades\EdenModal::modals() as $component)
    @livewire($component->component, $component->params)
@endforeach

@stack('js')

@livewireScripts

<!-- Assets - Scripts -->
@foreach(\BestSnipp\Eden\Facades\EdenAssets::scripts() as $script)
<script data-key="{{ $script['key'] }}" src="{{ $script['url'] }}"></script>
@endforeach

@include('eden::widgets.toasts')

<script src="{{ mix('/js/eden.js', 'vendor/eden') }}"></script>
@vite('resources/js/app.js')

<script>
    /*document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('component.initialized', (component) => {
            // console.log("component.initialized")
            // console.log(component)
            // console.log('==========================================')
        })
        Livewire.hook('element.initialized', (el, component) => {
            // console.log("element.initialized")
            // console.log(el, component)
            // console.log('==========================================')
        })
        Livewire.hook('element.updating', (fromEl, toEl, component) => {
            // console.log("element.updating")
            // console.log(fromEl, toEl, component)
            // console.log('==========================================')
        })
        Livewire.hook('element.updated', (el, component) => {
            // console.log("element.updated")
            // console.log(el, component)
            // console.log('==========================================')
        })
        Livewire.hook('element.removed', (el, component) => {
            // console.log("element.removed")
            // console.log(el, component)
            // console.log('==========================================')
        })
        Livewire.hook('message.sent', (message, component) => {
            // console.log("message.sent")
            // console.log(message, component)
            // console.log('==========================================')
        })
        Livewire.hook('message.failed', (message, component) => {
            // console.log("message.failed")
            // console.log(message, component)
            // console.log('==========================================')
        })
        Livewire.hook('message.received', (message, component) => {
            // console.log("message.received")
            // console.log(message, component)
            // console.log('==========================================')
        })
        Livewire.hook('message.processed', (message, component) => {
            // console.log("message.processed")
            // console.log(message, component)
            // console.log('==========================================')
        })
    });*/
</script>
</body>
</html>
