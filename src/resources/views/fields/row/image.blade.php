<div class="w-full">
    @if(!empty($value))
        @if(is_array($value))
            <div class="flex items-center">
                <div class="flex -space-x-2">
                    @foreach(array_slice($value, 0, 4) as $image)
                        <span class="relative inline-flex items-center justify-center w-10 text-white rounded-full">
                            <img src="{{ asset($image) }}" alt="{{ $image }}" class="border-2 border-white rounded-full aspect-square object-cover hover:scale-[2.5]" />
                        </span>
                    @endforeach
                    @if(count($value) > 4)
                        <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm border-2 border-white rounded-full bg-slate-200 text-slate-500">
                            +{{ count($value) - 4 }}
                        </span>
                    @endif
                </div>
            </div>
        @else
            <span class="relative inline-flex items-center justify-center w-10 text-white rounded-full group">
                <img src="{{ asset($value) }}" alt="{{ $value }}" class="border-2 border-white rounded-full aspect-square object-cover hover:scale-[2.5]" />
            </span>
        @endif
    @endif
</div>
