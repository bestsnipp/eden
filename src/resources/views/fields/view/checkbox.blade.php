<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        <div class="relative grid grid-cols-1 {{ $cols }}">
            @foreach($options as $optionKey => $optionValue)
                @if(!$hideUnchecked)
                    <div class="mt-1">
                        <label class="inline-flex items-center">
                    <span class="h-5 w-5 inline-flex items-center justify-center text-slate-500 border border-gray-300 rounded">
                        @if(in_array($optionKey, $value))
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        @endif
                    </span>
                            <span class="ml-3">{{ $optionValue }}</span>
                        </label>
                    </div>
                @elseif(in_array($optionKey, $value))
                    <div class="mt-1">
                        <label class="inline-flex items-center">
                <span class="h-5 w-5 inline-flex items-center justify-center text-slate-500 border border-gray-300 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                            <span class="ml-3">{{ $optionValue }}</span>
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
