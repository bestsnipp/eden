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
        <button class="inline-flex items-center gap-2 px-3 py-2 bg-slate-800 border border-transparent rounded-md text-white hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:bg-slate-700 dark:hover:bg-slate-600"
            :class="@json($active) ? 'bg-primary-500 text-white shadow-md shadow-primary-300' : 'text-gray-700'">
            @if($icon) <span class="scale-75">{!! edenIcon($icon) !!}</span> @endif
            <span class="grow">{{ $title }}</span>
        </button>
    </form>
@else
    <a
        href="{{ $route }}"
        @if($inNewTab) target="_blank" @endif
        class="inline-flex items-center gap-2 px-3 py-2 bg-slate-800 border border-transparent rounded-md text-white hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:bg-slate-700 dark:hover:bg-slate-600"
        :class="@json($active) ? 'bg-primary-500 text-white shadow-md shadow-primary-300' : 'text-gray-700'">
        @if($icon) <span class="scale-75">{!! edenIcon($icon) !!}</span> @endif
        <span class="grow">{{ $title }}</span>
    </a>
@endif
