<div class="w-full">
    @if(!empty($value))
        @if(is_array($value))
            <span>{{ count($value) }} {{ \Illuminate\Support\Str::pluralStudly('File', count($value)) }}</span>
        @else
            <span>1 File</span>
        @endif
    @endif
</div>
