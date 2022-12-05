@if($type == 'button')
    <a href="#"
       wire:click.prevent="applyAction('{{ $action->getKey() }}', '{{ $this->getRecordIdentifier($record) }}')"
       x-data="{ tooltip: '{{ $action->getTitle() }}' }"
       x-tooltip="tooltip"
       title="{{ $action->getTitle() }}"
       class="{{ $buttonStyle }}">
        @if(!is_null($action->icon))
            {!! edenIcon($action->icon, $iconSize) !!}
        @else
            {!! edenIcon('cursor-arrow-rays', $iconSize) !!}
        @endif
    </a>
@else
    <li @click="isOpen = false" wire:click="applyAction('{{ $action->getKey() }}', '{{ $this->getRecordIdentifier($record) }}')" class="py-2 px-3 rounded cursor-pointer transition hover:bg-indigo-50 text-slate-500">
        <div wire:loading.remove wire:target="applyAction('{{ $action->getKey() }}', '{{ json_encode($record) }}')" class="flex items-center gap-3">
            @if(!is_null($action->icon))
                {!! edenIcon($action->icon, $iconSize) !!}
            @endif
            <span class="text-left">{{ $action->getTitle() }}</span>
        </div>
        <div wire:loading.flex wire:target="applyAction('{{ $action->getKey() }}')" class="gap-3">
            {!! edenIcon('dots-horizontal', $iconSize) !!}
            <span class="text-left">Please Wait ...</span>
        </div>
    </li>
@endif
