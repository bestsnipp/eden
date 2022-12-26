<div class="relative" x-data="{show: false, selected: 'system'}"
     x-init="$nextTick(() => {
        selected = localStorage.edenTheme || 'system';
     })">
    <button type="button" @click.prevent="show = !show" class="rounded-full transition p-1 text-gray-400 hover:text-primary-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-500 dark:focus:ring-slate-800">
        <span class="sr-only">View notifications</span>
        <template x-if="selected === 'light'"><span>{!! edenIcon('sun') !!}</span></template>
        <template x-if="selected === 'dark'"><span>{!! edenIcon('moon') !!}</span></template>
        <template x-if="selected !== 'light' && selected !== 'dark'"><span>{!! edenIcon('sparkles') !!}</span></template>
    </button>
    <div x-show="show" x-on:click.outside="show = false" x-transition x-cloak
         class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md overflow-hidden bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none text-slate-500 dark:bg-slate-700 dark:text-white">
        <ul class="p-0.5">
            <li><a class="flex gap-3 px-3 py-1.5 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-600" @click.prevent="switchColorScheme('light'); show = false; selected = 'light';" href="#">{!! edenIcon('sun') !!} <span>Light</span></a></li>
            <li><a class="flex gap-3 px-3 py-1.5 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-600" @click.prevent="switchColorScheme('dark'); show = false; selected = 'dark';" href="#">{!! edenIcon('moon', 'scale-75') !!} <span>Dark</span></a></li>
            <li><a class="flex gap-3 px-3 py-1.5 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-600" @click.prevent="switchColorScheme('system'); show = false; selected = 'system';" href="#">{!! edenIcon('sparkles', 'scale-75') !!} <span>System</span></a></li>
        </ul>
    </div>
</div>
