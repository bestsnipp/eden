<li x-data="{active: @JSON($active)}">
    <a href="#" @click="active = !active"
       class="flex items-center px-3 py-2 mt-1 rounded-md transition-all duration-200 ease-in hover:pl-5 text-slate-600"
       :class="active ? 'bg-gray-100' : ''">
        <span class="scale-95">{!! $icon !!}</span>
        <span class="grow mx-2">{{ $title }}</span>
        <span class="text-slate-500 transition-all ease-in-out duration-150" :class="active ? 'rotate-90' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </span>
    </a>
    @if(count($items) > 0)
    <ul data-dusk="subMenu" class="" x-show="active" x-transition @if(!$active) x-cloak @endif>
        @foreach($items as $item)
            {!! $item->render() !!}
        @endforeach
    </ul>
    @endif
</li>
