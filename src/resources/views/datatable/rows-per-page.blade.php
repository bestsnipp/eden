<div class="border border-2 border-slate-200 flex flex-col rounded-md overflow-hidden mb-3 dark:border-slate-600">
    <div class="flex justify-between items-center py-2 px-3 bg-slate-200 text-slate-500 dark:bg-slate-500 dark:text-slate-200">
        <label class="" for="{{ $key }}">{{ $title }}</label>
        @if(!empty($value) && ($value != $initial))
            <button wire:click="$set('{{$key}}', '{{$initial}}')" class="text-slate-400 hover:text-red-400">clear</button>
        @endif
    </div>
    <div class="relative group block filterContainer">
        <select wire:model.defer="{{ $key }}" class="relative border-0 group block w-full py-2 px-3 dark:bg-slate-400 dark:text-slate-100" id="{{ $key }}">
            @foreach($options as $optionKey => $optionValue)
                <option value="{{ $optionKey }}">{{ $optionValue }}</option>
            @endforeach
        </select>
    </div>
</div>
