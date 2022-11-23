<div class="px-4 mt-2 mb-1">
    <div data-dusk="listMetric" class="-max-h-44 overflow-y-auto">
        @foreach($items as $item)
            <div class="flex items-center justify-between text-slate-500 mt-2 border-t pt-2 first:pt-0 first:border-t-0">
                @if(!empty($item['icon']))
                <span class="mr-2">
                    {!! $item['icon'] !!}
                </span>
                @endif
                <div class="grow max-w-full @if($singleLine) truncate @endif">
                    @if(!empty($item['title']))<h4 class="font-bold @if($singleLine) truncate @endif">{{ $item['title'] }}</h4>@endif
                    @if(!empty($item['description']))<p class="text-sm @if($singleLine) truncate @endif">{{ $item['description'] }}</p>@endif
                </div>
                @if(!empty($item['actions']) && is_array($item['actions']))
                    <div class="">
                        <div class="relative inline-flex" x-data="{isOpen: false, selected: ''}" @click.away="isOpen = false">
                            <div @click="isOpen = !isOpen" class="relative px-2 py-2 bg-slate-50 rounded-md flex items-center cursor-pointer w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </div>
                            <div x-show="isOpen" x-transition.scale class="absolute z-50 border border-slate-50 mt-2 rounded-md shadow origin-top-right right-0 bg-white shadow-lg px-1 py-1 bg-white rounded-md w-44" style="display: none;">
                                <ul class="list-inside text-sm">
                                    @foreach($item['actions'] as $action)
                                        {!! $action->render() !!}
                                        {{--<li @click="isOpen = false" class="py-2 px-3 rounded cursor-pointer transition hover:bg-indigo-50 text-slate-500">
                                            <div class="flex">
                                                @if(true)
                                                    <span class="mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                        </svg>
                                                    </span>
                                                @endif
                                                <span class="text-sm">Action Title</span>
                                            </div>
                                        </li>--}}
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
