<div class="w-full">
    @if(!empty($value))
        @if(is_array($value))
            <span>{{ count($value) }} {{ \Illuminate\Support\Str::pluralStudly('Image', count($value)) }}</span>
        @else
            <span><img src="{{ asset($value) }}" alt="{{ $value }}" class="border border-slate-100 rounded-md overflow-hidden w-12 h-12 object-cover" /></span>
        @endif
    @endif
</div>
