<div class="fixed w-full md:w-2/12 flex flex-col overflow-auto z-[999] empty:hidden pb-5 px-1 {{ [
        'top-right' => 'top-1.5 right-2.5',
        'top-left' => 'top-1.5 left-3',
        'bottom-right' => 'bottom-0 right-2.5',
        'bottom-left' => 'bottom-0 left-3',
        'top-center' => 'top-1.5 left-1/2 -translate-x-1/2',
        'bottom-center' => 'bottom-0 left-1/2 -translate-x-1/2',
        'center-center' => 'top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2',
    ][config('eden.toast')] }}"
    x-data="{toasts: [], closeClass: {normal: 'text-slate-400', error: 'text-red-400', warning: 'text-orange-400', success: 'text-green-400'}}"
    @toast.window="event => {
        toasts.push(event.detail)
    }">
    <template x-for="(toast, index) in toasts.reverse()" :key="toast.hash">
        <div x-data="{show: false}"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-75"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-75"
             class="mt-2 bg-white shadow-lg overflow-hidden rounded-md w-full relative"
            x-init="$nextTick(() => {
                show = true;
                setTimeout(() => {show = false}, {{ config('eden.toast_duration', 5000) }});

                $watch('show', value => {
                    value === false ? setTimeout(() => {toasts.splice(index, 1)}, 76) : '';
                })
            })">
            <div class="py-2 rounded-md px-3 flex items-center m-1 bg-slate-100" :class="toast.class">
                {!! edenIcon('bell', 'scale-75') !!}
                <h4 class="grow font-medium ml-2" x-text="toast.title">Normal</h4>
                <a href="#" :class="closeClass[toast.type]" @click.prevent="show = false">
                    {!! edenIcon('x-mark') !!}
                </a>
            </div>
            <div class="pt-2 pb-3 px-3">
                <p class="font-medium text-slate-500 text-sm" x-text="toast.message"></p>
            </div>
        </div>
    </template>
</div>
