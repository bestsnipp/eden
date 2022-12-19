<div x-data="{currentValue: @entangle('filters.'.$key).defer}"
     class="border border-2 border-slate-200 flex flex-col rounded overflow-hidden mb-3 dark:border-slate-500">
    <div class="flex justify-between items-center py-2 px-3 bg-slate-200 text-slate-500 dark:bg-slate-500 dark:text-slate-200">
        <label class="" for="{{ $key }}">{{ $title }}</label>
        @if(!empty($value) && ($initial != $value))
            <button wire:click="removeFilter('{{$key}}', {{$initial}})" class="text-slate-400 hover:text-red-400">clear</button>
        @endif
    </div>
    <div class="relative group p-1 flex flex-row justify-center items-center dark:bg-slate-600 dark:text-slate-100">
        <button type="button" @click="currentValue--" class="bg-slate-100 text-slate-700 p-3 rounded-l-md dark:bg-slate-500 dark:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
            </svg>
        </button>
        <input type="text" x-ref="numberField" x-model.number="currentValue" wire:model.defer="filters.{{$key}}" id="{{ $key }}" placeholder="0"
               class="relative border-0 group block w-full appearance-none ring-0 focus:ring-0 hover:ring-0 text-center dark:bg-slate-600" />
        <button type="button" @click="currentValue++" class="bg-slate-100 text-slate-700 p-3 rounded-r-md dark:bg-slate-500 dark:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>
</div>
