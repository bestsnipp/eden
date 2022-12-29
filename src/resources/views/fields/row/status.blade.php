<div class="inline-flex items-center gap-2">
    @if(!empty($value))
    <span class="{{ $style }} font-bold pl-1 pr-3 py-0.5 rounded-full text-sm inline-flex items-center gap-1">
        {!! edenIcon($icon, $iconClass) !!}
        {{ $value }}
    </span>
    @endif
</div>
