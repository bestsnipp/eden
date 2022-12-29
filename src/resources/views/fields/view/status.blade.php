<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        @if(!empty($value))
            <span class="{{ $style }} font-bold pl-1 pr-3 py-0.5 rounded-full text-sm inline-flex items-center gap-1">
                {!! edenIcon($icon, $iconClass) !!}
                {{ $value }}
            </span>
        @endif
    </div>
</div>
