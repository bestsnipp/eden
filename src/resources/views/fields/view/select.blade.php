<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        @if(is_array($value))
            @foreach($value as $val)
                <span data-separator="," class="relative after:content-[attr(data-separator)] after:ml-0.5 after:font-bold last:after:hidden">{{ isset($options[$val]) ? $options[$val] : $val }}</span>
            @endforeach
        @else
            {{ isset($options[$value]) ? $options[$value] : $value }}
        @endif
    </div>
</div>
