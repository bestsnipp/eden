<div class="fixed w-full md:w-2/12 flex flex-col overflow-auto z-[9999] {{ [
        'top-right' => 'top-0 right-5',
        'top-left' => 'top-0 left-5',
        'bottom-right' => 'bottom-0 right-5',
        'bottom-left' => 'bottom-0 left-5',
        'top-center' => 'top-0 left-1/2 -translate-x-1/2',
        'bottom-center' => 'bottom-0 left-1/2 -translate-x-1/2',
        'center-center' => 'top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2',
    ][config('eden.toast')] }}"
    x-data="{toasts: [], closeClass: {normal: 'text-slate-400', error: 'text-red-400', warning: 'text-orange-400', success: 'text-green-400'}}"
    @toast.window="event => {
        toasts.push(event.detail)
    }">
    <template x-for="(toast, index) in toasts.reverse()" :key="toast.hash">
        <div x-data="{show: false}" x-show="show" x-transition
            class="mt-2 bg-white shadow-lg overflow-hidden rounded-md w-full relative"
            x-init="$nextTick(() => show = true); setTimeout(() => {toasts.splice(index, 1)}, {{ config('eden.toast_duration', 5000) }});">
            <div class="py-2 rounded-md px-3 flex items-center m-1 bg-slate-100" :class="toast.class">
                <span class="scale-75">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </span>
                <h4 class="grow font-medium ml-2" x-text="toast.title">Normal</h4>
                <a href="#" :class="closeClass[toast.type]" @click.prevent="toasts.splice(index, 1)">
                    {!! edenIcon('x-mark') !!}
                </a>
            </div>
            <div class="pt-2 pb-3 px-3">
                <p class="font-medium text-slate-500 text-sm" x-text="toast.message"></p>
            </div>
        </div>
    </template>
</div>
