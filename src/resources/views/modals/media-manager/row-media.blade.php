<div class="bg-slate-50 p-0.5 border-2 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 cursor-pointer transition-all relative dark:bg-slate-500"
     x-bind:class="{
        'border-primary-500 dark:border-primary-300': _.findIndex(selected, ['id', '{{ $file['id'] }}']) > -1,
        'border-slate-100 dark:border-slate-500': _.findIndex(selected, ['id', '{{ $file['id'] }}']) <= -1
    }"
     x-on:click.prevent="() => {
        if (selectionType === 'single' && selected.length > 0) {
            selected = [];
        }
        let selectedItemIndex = _.findIndex(selected, ['id', '{{ $file['id'] }}']);
        if (selectedItemIndex > -1) {
            selected.splice(selectedItemIndex, 1);
        } else {
            if (selectionType === 'single' && selected.length <= 0) {
                selected.push(@js($file));
            } else if (selectionType !== 'single') {
                selected.push(@js($file));
            }
        }
    }">
    <template x-if="_.findIndex(selected, ['id', '{{ $file['id'] }}']) > -1">
        <span class="absolute bg-white -top-2.5 -right-2.5 text-primary-500 scale-125 rounded-full">{!! edenIcon('check-circle-solid') !!}</span>
    </template>
    @if($file['preview'])
        <img src="{{ $file['url'] }}" alt="" class="w-full h-full object-cover rounded-sm" />
    @else
        <div class="w-10 h-14 bg-slate-200 rounded-sm relative flex flex-col justify-center">
            <span class="border-[7px] border-slate-400 border-r-slate-50 border-t-slate-50 absolute right-0 top-0 dark:border-r-slate-500 dark:border-t-slate-500"></span>
            <span class="{{ isset($colors[$file['extension']]) ? $colors[$file['extension']] : 'bg-primary-500 text-white'  }} inline-flex justify-center top-auto bottom-auto text-xs font-bold tracking-wide px-2.5 py-1 mt-3 -left-3 relative rounded-sm uppercase">
                {{ $file['extension'] }}
            </span>
        </div>
        <p class="truncate w-full px-3" dir="rtl">{{ $file['name'] }}</p>
    @endif
</div>
