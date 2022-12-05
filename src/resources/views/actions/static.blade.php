@if($type != 'button') <li class="w-full"> @endif
@if($isForm)
    <form class="inline"
        action="{{ $route }}"
        @if(strtoupper($method) == 'GET') method="GET" @else method="POST" @endif
        @if($inNewTab) target="_blank" @endif >

        @if(!in_array(strtoupper($method), ['GET', 'POST'])) @method($method) @endif

        @if($formWithCsrf) @csrf @endif

        @foreach($data as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
        @endforeach

        @if($type == 'button')
            <button x-data="{ tooltip: '{{ $action->getTitle() }}' }" x-tooltip="tooltip" title="{{ $action->getTitle() }}" class="{{ $buttonStyle }}">
                @if(!is_null($action->icon))
                    {!! edenIcon($action->icon, $iconSize) !!}
                @else
                    {!! edenIcon('cursor-arrow-rays', $iconSize) !!}
                @endif
            </button>
        @else
            <button @click="isOpen = !isOpen" class="flex items-center gap-3 py-2 px-3 rounded cursor-pointer transition w-full block hover:bg-indigo-50 text-slate-500">
                @if(!is_null($action->icon))
                    {!! edenIcon($action->icon, $iconSize) !!}
                @endif
                <span class="text-left">{{ $action->getTitle() }}</span>
            </button>
        @endif
    </form>
@else
    @if($type == 'button')
        <a href="{{ $route }}" x-data="{ tooltip: '{{ $action->getTitle() }}' }" x-tooltip="tooltip" title="{{ $action->getTitle() }}" class="{{ $buttonStyle }}"
            @if($inNewTab) target="_blank" @endif >
            @if(!is_null($action->icon))
                {!! edenIcon($action->icon, $iconSize) !!}
            @else
                {!! edenIcon('cursor-arrow-rays', $iconSize) !!}
            @endif
        </a>
    @else
        <a href="{{ $route }}"
            @if($inNewTab) target="_blank" @endif
            class="flex items-center gap-3 py-2 px-3 rounded cursor-pointer transition w-full block hover:bg-indigo-50 text-slate-500">
            @if(!is_null($action->icon))
                {!! edenIcon($action->icon, $iconSize) !!}
            @endif
            <span class="text-left">{{ $action->getTitle() }}</span>
        </a>
    @endif
@endif
@if($type != 'button') </li> @endif
