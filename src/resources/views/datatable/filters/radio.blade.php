<div class="border border-2 border-slate-200 flex flex-col rounded overflow-hidden mb-3 dark:border-slate-600">
    <div class="flex justify-between items-center py-2 px-3 bg-slate-200 text-slate-500 dark:bg-slate-500 dark:text-slate-200">
        <label class="" for="{{ $uid }}">{{ $title }}</label>
        @if(!empty($value) && $initial != $value)
            <button wire:click="removeFilter('{{$key}}', [])" class="text-slate-400 hover:text-red-400">clear</button>
        @endif
    </div>
    <div class="relative group block px-3 py-2 dark:bg-slate-400 dark:text-slate-100">
        @foreach($options as $optionKey => $optionValue)
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" value="{{ $optionKey }}" wire:model.defer="filters.{{$key}}" name="{{$key}}" class="focus:ring-0 h-5 w-5 text-indigo-600 border-gray-300 dark:bg-slate-500 dark:border-slate-500">
                    <span class="ml-3 text-sm">{{ $optionValue }}</span>
                </label>
            </div>
        @endforeach
    </div>
</div>
