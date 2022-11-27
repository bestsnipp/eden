<div class="flex justify-end gap-2">
    @if(count($actions) <= config('eden.action_buttons_count', 3))
        @foreach($actions as $action)
            <a href="#"
               wire:click.prevent="applyAction('{{ $action->getKey() }}', '{{ $this->getPrimaryKey($row) }}')"
               x-data="{ tooltip: '{{ $action->title }}' }"
               x-tooltip="tooltip"
               class="{{ $buttonStyle }}" title="{{ $action->title }}">
                @if(!is_null($action->icon))
                    {!! $action->icon !!}
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </a>
        @endforeach
    @else
    <div class="relative inline-flex" x-data="{isOpen: false}" @click.away="isOpen = false">
        <div @click="isOpen = !isOpen" class="relative px-2 py-2 bg-slate-50 rounded-md flex items-center cursor-pointer w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </div>
        <div x-show="isOpen" x-transition.scale class="absolute z-50 border border-slate-50 mt-2 rounded-md shadow origin-top-right right-0 bg-white shadow-lg px-1 py-1 bg-white rounded-md w-44" style="display: none;">
            <ul class="list-inside">
                @foreach($actions as $action)
                    <li @click="isOpen = false" wire:click="applyAction('{{ $action->getKey() }}', '{{ $this->getPrimaryKey($row) }}')" class="py-2 px-3 rounded cursor-pointer transition hover:bg-indigo-50 text-slate-500">
                        <div wire:loading.remove wire:target="applyAction('{{ $action->getKey() }}', '{{ $this->getPrimaryKey($row) }}')" class="flex items-center">
                            @if(!is_null($action->icon))
                                <span class="mr-3">{!! $action->icon !!}</span>
                            @endif
                            <span class="text-left">{{ $action->title ?? '' }}</span>
                        </div>
                        <div wire:loading.flex wire:target="applyAction('{{ $action->getKey() }}')">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </span>
                            <span class="text-left">Please Wait ...</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
