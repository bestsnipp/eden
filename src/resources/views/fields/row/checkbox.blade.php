<div class="w-full">
    @foreach($options as $optionKey => $optionValue)
        @if(in_array($optionKey, $value))
            <span class="m-0.5">{{ $optionValue }}</span>
        @endif
    @endforeach
</div>
