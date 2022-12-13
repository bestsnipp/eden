<div x-data x-ref="parent" class="{{ $styleCard }} {{ $compWidth }} {{ $compHeight }}">
    <div class="relative h-full">
        <div class="flex flex-col items-stretch">
            @if(!$blankCanvas)
            <div class="px-4 pt-4">
                <div class="flex justify-between items-start">
                    @if(!empty($title))
                        <h6 class="text-slate-500 font-medium self-center max-w-2/3">{{ $title }}</h6>
                    @else
                        <span></span>
                    @endif
                    @if($hasFilters)
                        <select wire:model="filter" class="w-1/3 py-2 px-3 border border-gray-300 pr-7 bg-white rounded-md focus:outline-none focus:ring-primary-200 focus:border-primary-500 sm:text-xs">
                            @foreach($filters as $filterKey => $label)
                                <option value="{{ $filterKey }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            @endif

            @if(!is_null($data) && ($data instanceof \BestSnipp\Eden\Components\Metrics\MetricValue))
                @if(!is_null($data->view()))
                    {!! $data->render() !!}
                @else
                    <p class="px-4 py-4 text-red-300 break-words">{{ sprintf('Unable to find view for current %s', get_class($data)) }}</p>
                @endif
            @else
                <p class="px-4 py-4 text-red-300 break-words">{{ sprintf('Provided value should be type of %s', \BestSnipp\Eden\Components\Metrics\MetricValue::class) }}</p>
            @endif
        </div>
        <div wire:loading.delay.short.flex class="absolute inset-0 bg-white/[0.75] hidden justify-center items-center rounded-md">
            <span class="animate-pulse text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                </svg>
            </span>
        </div>
    </div>
</div>
