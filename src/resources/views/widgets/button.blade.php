@if($isForm)
    <form
        action="{{ $route }}"
        @if(strtoupper($method) == 'GET') method="GET" @else method="POST" @endif
        @if($inNewTab) target="_blank" @endif >

        @if(!in_array(strtoupper($method), ['GET', 'POST'])) @method($method) @endif

        @if($formWithCsrf) @csrf @endif

        @foreach($data as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
        @endforeach
        <button class="{{ config('eden.button_style') }}"
            :class="@json($active) ? 'bg-primary-500 shadow-md shadow-primary-300' : ''">
            @if($icon) <span class="scale-75">{!! edenIcon($icon) !!}</span> @endif
            <span class="grow">{{ $title }}</span>
        </button>
    </form>
@else
    <a
        href="{{ $route }}"
        @if($inNewTab) target="_blank" @endif
        class="{{ config('eden.button_style') }}"
        :class="@json($active) ? 'bg-primary-500 shadow-md shadow-primary-300' : ''">
        @if($icon) <span class="scale-75">{!! edenIcon($icon) !!}</span> @endif
        <span class="grow">{{ $title }}</span>
    </a>
@endif
