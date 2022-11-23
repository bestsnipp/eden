<button type="button" class="rounded-full transition p-1 text-gray-400 hover:text-primary-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-500">
    <span class="sr-only">View notifications</span>
    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
    </svg>
</button>

<div class="relative" x-data="{show: false, selected: ''}">
    <button
        x-on:click.prevent="show = !show"
        type="button"
        data-dusk="user-menu-button"
        class="flex items-center gap-3 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-500"
        aria-expanded="false"
        aria-haspopup="true">
        <span class="sr-only">Open user menu</span>
        <img class="h-9 w-9 rounded-full" src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?s=256" alt="">
    </button>
    <div x-show="show"
         x-transition
         x-on:click.outside="show = false"
         x-cloak
         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none text-sm text-slate-500"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="user-menu-button"
         tabindex="-1">
        <ul data-dusk="mainAccountMenu" class="px-1 py-4">
            {{--@foreach(\Dgharami\Eden\Eden::accountMenu() as $menuItem)
                {!! $menuItem->render() !!}
            @endforeach--}}
        </ul>
    </div>
</div>
