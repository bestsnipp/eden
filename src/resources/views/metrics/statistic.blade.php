<div class="px-4 my-4">
    <div class="flex items-center">
        <div class="grow">
            <h2 class="font-bold text-2xl text-slate-600 mt-3 mb-3 flex">{!! $valueLabel !!}</h2>
            <div class="flex items-center">
                @if($change > 0)
                    <span class="text-green-500 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </span>
                @endif
                @if($change < 0)
                    <span class="text-red-500 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </span>
                @endif
                <p class="text-slate-500">
                    @if($change > 0)<span class="font-medium text-green-500">{{ $change }}%</span> Increased @endif
                    @if($change < 0)<span class="font-medium text-red-500">{{ $change }}%</span> Decreased @endif
                    @if($change == 0) No Value for Analysis @endif
                </p>
            </div>
        </div>
        @if($showIcon)
            <div class="ml-3">
                <span class="p-4 rounded-full {{ $iconColor }} relative inline-block">{!! $icon !!}</span>
            </div>
        @endif
    </div>
</div>
