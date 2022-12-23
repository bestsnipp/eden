<div class="px-4 my-4">
    <div class="flex items-center">
        <div class="grow">
            <h2 class="font-bold text-2xl text-slate-600 mt-3 mb-3 flex dark:text-slate-200">{!! $valueLabel !!}</h2>
            <div class="flex items-center">
                @if($change > 0)
                    <span class="text-green-500 mr-2">
                        {!! edenIcon('trending-up') !!}
                    </span>
                @endif
                @if($change < 0)
                    <span class="text-red-500 mr-2">
                        {!! edenIcon('trending-down') !!}
                    </span>
                @endif
                @if($showPerntageChange)
                <p class="text-slate-500 dark:text-slate-300">
                    @if($change > 0)<span class="font-medium text-green-500">{{ $change }}%</span> Increased @endif
                    @if($change < 0)<span class="font-medium text-red-500">{{ $change }}%</span> Decreased @endif
                    @if($change == 0) No Value for Analysis @endif
                </p>
                @endif
            </div>
        </div>
        @if($showIcon)
            <div class="ml-3">
                <span class="p-4 rounded-full {{ $iconColor }} relative inline-block">{!! $icon !!}</span>
            </div>
        @endif
    </div>
</div>
