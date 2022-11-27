<div class="flex justify-end gap-2">
    @if(count($actions) <= config('eden.action_buttons_count', 3))
        @foreach($actions as $action)
            <a href="#"
               wire:click.prevent="applyAction('{{ $action->getKey() }}', '{{ $this->getRecordIdentifier($record) }}')"
               x-data="{ tooltip: '{{ $action->getTitle() }}' }"
               x-tooltip="tooltip"
               class="{{ $buttonStyle }}" title="{{ $action->getTitle() }}">
                @if(!is_null($action->icon))
                    {!! edenIcon($action->icon, 'scale-50') !!}
                @else
                    {!! edenIcon('cursor-arrow-rays', 'scale-50') !!}
                @endif
            </a>
        @endforeach
    @else
    <div class="relative inline-flex" x-data="{isOpen: false}" @click.away="isOpen = false">
        <div @click="isOpen = !isOpen" class="relative px-2 py-1.5 bg-slate-50 rounded-md flex items-center cursor-pointer w-auto">{!! edenIcon('dots-vertical', 'scale-75') !!}</div>
        <div x-show="isOpen" x-transition.scale class="absolute z-50 border border-slate-50 mt-2 rounded-md shadow origin-top-right right-0 bg-white shadow-lg px-1 py-1 bg-white rounded-md w-44" style="display: none;">
            <ul class="list-inside">
                @foreach($actions as $action)
                    <li @click="isOpen = false" wire:click="applyAction('{{ $action->getKey() }}', '{{ $this->getRecordIdentifier($record) }}')" class="py-2 px-3 rounded cursor-pointer transition hover:bg-indigo-50 text-slate-500">
                        <div wire:loading.remove wire:target="applyAction('{{ $action->getKey() }}', '{{ json_encode($record) }}')" class="flex items-center gap-3">
                            @if(!is_null($action->icon))
                                {!! edenIcon($action->icon, 'scale-75') !!}
                            @endif
                            <span class="text-left">{{ $action->getTitle() }}</span>
                        </div>
                        <div wire:loading.flex wire:target="applyAction('{{ $action->getKey() }}')" class="gap-3">
                            {!! edenIcon('dots-horizontal', 'scale-75') !!}
                            <span class="text-left">Please Wait ...</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
