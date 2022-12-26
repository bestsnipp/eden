<div class="relative" x-data="{show: false, selected: ''}">
    <button
        x-on:click.prevent="show = !show"
        type="button"
        data-dusk="user-menu-button"
        class="flex items-center gap-3 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-500 dark:focus:ring-slate-800"
        aria-expanded="false"
        aria-haspopup="true">
        <span class="sr-only">Open user menu</span>
        <img class="h-9 w-9 rounded-full" src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?s=256" alt="">
    </button>
    <div x-show="show"
         x-transition
         x-on:click.outside="show = false"
         x-cloak
         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none text-slate-500 dark:bg-slate-700 dark:text-white dark:shadow-slate-800"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="user-menu-button"
         tabindex="-1">
        <ul data-dusk="mainAccountMenu" class="px-1 py-4">
            @foreach($menu as $menuItem)
                {!! $menuItem->render() !!}
            @endforeach
        </ul>
    </div>
</div>
