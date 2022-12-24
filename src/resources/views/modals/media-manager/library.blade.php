<div class="flex gap-3 divide-x divide-slate-200 dark:divide-slate-500">
    <div class="grow">
        @livewire(\BestSnipp\Eden\Assembled\MediaManager\MediaManagerDataTable::getName())
        {{--<div class="grid grid-cols-1 gap-4"
            x-bind:class="{
                'md:grid-cols-2 lg:grid-cols-3': selected.length > 0,
                'md:grid-cols-3 lg:grid-cols-4': selected.length <= 0
            }">
            @foreach($files as $file)
                <div class="bg-slate-50 p-0.5 border-2 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 cursor-pointer transition-all relative"
                    x-bind:class="{
                        'border-primary-500': _.findIndex(selected, ['id', '{{ $file['id'] }}']) > -1,
                        'border-slate-100': _.findIndex(selected, ['id', '{{ $file['id'] }}']) <= -1
                    }"
                    x-on:click.prevent="() => {
                        let selectedItemIndex = _.findIndex(selected, ['id', '{{ $file['id'] }}']);
                        if (selectedItemIndex > -1) {
                            selected.splice(selectedItemIndex, 1);
                        } else {
                            selected.push(@js($file));
                        }
                    }">
                    <template x-if="_.findIndex(selected, ['id', '{{ $file['id'] }}']) > -1">
                        <span class="absolute bg-white -top-2.5 -right-2.5 text-primary-500 scale-125 rounded-full">{!! edenIcon('check-circle-solid') !!}</span>
                    </template>
                @if($file['preview'])
                    <img src="{{ $file['url'] }}" alt="" class="w-full h-full object-cover rounded-sm" />
                @else
                    <div class="w-10 h-14 bg-slate-200 rounded-sm relative flex flex-col justify-center">
                        <span class="border-[7px] border-slate-400 border-r-slate-50 border-t-slate-50 absolute right-0 top-0"></span>
                        <span class="{{ isset($colors[$file['extension']]) ? $colors[$file['extension']] : 'bg-primary-500 text-white'  }} inline-flex justify-center top-auto bottom-auto text-xs font-bold tracking-wide px-2.5 py-1 mt-3 -left-3 relative rounded-sm uppercase">
                        {{ $file['extension'] }}
                    </span>
                    </div>
                    <p class="truncate w-full px-3" dir="rtl">{{ $file['name'] }}</p>
                @endif
                </div>
            @endforeach
        </div>--}}
    </div>
    <template x-if="selected.length > 0">
        <div class="w-[120rem] pl-3">

            <div class="flex justify-between items-center relative">
                <h4 class="mb-3 text-xl">Preview</h4>
                <span class="-top-2 -right-2 text-red-500 opacity-50 hover:opacity-100 cursor-pointer rounded-full"
                    x-on:click.prevent="selected = []">Clear Selection</span>
            </div>

            <template x-if="selected.length === 1">
                <div>
                    <div class="bg-slate-50 p-0.5 border-2 border-slate-100 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 transition-all relative dark:bg-slate-500 dark:border-slate-400">
                        <template x-if="_.head(selected).preview === true">
                            <img x-bind:src="_.head(selected).url" alt="" class="w-full h-full object-cover rounded-sm" />
                        </template>
                        <template x-if="_.head(selected).preview !== true">
                            <div class="w-10 h-14 bg-slate-200 rounded-sm relative flex flex-col justify-center">
                                <span class="border-[7px] border-slate-400 border-r-slate-50 border-t-slate-50 absolute right-0 top-0"></span>
                                <span class="bg-primary-500 text-white inline-flex justify-center top-auto bottom-auto text-xs font-bold tracking-wide px-2.5 py-1 mt-3 -left-3 relative rounded-sm uppercase"
                                      x-text="_.head(selected).extension"></span>
                            </div>
                        </template>
                    </div>
                    <h3 class="text-lg my-3 text-primary-500 dark:text-primary-400 font-bold" x-text="_.head(selected).name"></h3>
                </div>
            </template>
            <template x-if="selected.length > 1">
                <div>
                    <div class="relative mb-12 mt-6">
                        <div class="bg-slate-50 p-0.5 border-2 border-slate-100 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 transition-all w-full absolute -rotate-[5deg] dark:bg-slate-500 dark:border-slate-400 dark:border"></div>
                        <div class="bg-slate-50 p-0.5 border-2 border-slate-100 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 transition-all w-full absolute rotate-[5deg] dark:bg-slate-500 dark:border-slate-400 dark:border"></div>
                        <div class="bg-slate-50 p-0.5 border-2 border-slate-100 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 transition-all w-full dark:bg-slate-500 dark:border-slate-400 dark:border"></div>
                        <div class="bg-slate-50 p-0.5 border-2 border-slate-100 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 transition-all w-full absolute top-0 dark:bg-slate-500 dark:border-slate-400 dark:border">
                            <p class="font-bold"><span x-text="selected.length"></span> Files Selected</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                        <template x-for="(currentItem, currentItemIndex) in selected">
                            <div class="bg-slate-50 p-0.5 border-2 border-slate-100 aspect-video rounded-md text-center flex flex-col justify-center items-center gap-2 transition-all relative dark:bg-slate-500 dark:border-slate-400">
                                <span
                                    class="absolute bg-white -top-2 -right-2 text-red-500 opacity-60 hover:opacity-100 cursor-pointer rounded-full dark:bg-slate-200"
                                    x-on:click.prevent="selected.splice(currentItemIndex, 1);"
                                    >{!! edenIcon('x-circle-solid') !!}</span>
                                <template x-if="currentItem.preview === true">
                                    <img x-bind:src="currentItem.url" alt="" class="w-full h-full object-cover rounded-md" />
                                </template>
                                <template x-if="currentItem.preview !== true">
                                    <div class="flex flex-col justify-center items-center gap-2 w-full h-full">
                                        <div class="w-10 h-14 bg-slate-200 rounded-sm relative flex flex-col justify-center">
                                            <span class="border-[7px] border-slate-400 border-r-slate-50 border-t-slate-50 absolute right-0 top-0 dark:border-r-slate-500 dark:border-t-slate-500"></span>
                                            <span class="bg-primary-500 text-white inline-flex justify-center top-auto bottom-auto text-xs font-bold tracking-wide px-2.5 py-1 mt-3 -left-3 relative rounded-sm uppercase"
                                                  x-text="currentItem.extension"></span>
                                        </div>
                                        <p class="text-xs w-full truncate px-2" dir="rtl" x-text="currentItem.name"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

        </div>
    </template>
</div>
