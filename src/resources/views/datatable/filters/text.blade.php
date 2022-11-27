<div class="border border-2 border-slate-200 flex flex-col rounded overflow-hidden mb-3">
    <div class="flex justify-between items-center py-2 px-3 bg-slate-200 text-slate-500">
        <label for="{{ $uid }}">{{ $title }}</label>
        @if(!empty($value) && ($initial !== $value))
            <button wire:click="removeFilter('{{$key}}', '{{ $initial }}')" class="text-slate-400 hover:text-red-400">clear</button>
        @endif
    </div>
    <input type="text" wire:model.defer="filters.{{$key}}" class="relative border-0 group block w-full focus:ring-0" id="{{ $uid }}" placeholder="..."/>
</div>
