<div class="w-full">
    @if(is_array($value))
        @foreach($value as $val)
            <span data-separator="," class="relative after:content-[attr(data-separator)] after:ml-0.5 after:font-bold last:after:hidden">{{ isset($options[$val]) ? $options[$val] : $val }}</span>
        @endforeach
    @else
        {{ isset($options[$value]) ? $options[$value] : $value }}
    @endif
</div>
