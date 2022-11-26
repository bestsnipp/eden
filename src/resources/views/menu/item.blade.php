<li>
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
            <button
                class="flex items-center gap-3 px-3 py-2 mt-1 rounded-md transition-all duration-200 ease-in hover:pl-5"
                :class="@json($active) ? 'bg-primary-500 text-white shadow-md shadow-primary-300' : 'text-gray-700'">
                @if($icon) <span class="scale-75">{!! edenIcon($icon) !!}</span> @endif
                <span class="grow">{{ $title }}</span>
            </button>
        </form>
    @else
        <a
            href="{{ $route }}"
            @if($inNewTab) target="_blank" @endif
            class="flex items-center gap-3 px-3 py-2 mt-1 rounded-md transition-all duration-200 ease-in hover:pl-5"
            :class="@json($active) ? 'bg-primary-500 text-white shadow-md shadow-primary-300' : 'text-gray-700'">
            @if($icon) <span class="scale-75">{!! edenIcon($icon) !!}</span> @endif
            <span class="grow">{{ $title }}</span>
        </a>
    @endif
</li>
