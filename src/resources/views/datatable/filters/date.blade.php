<div class="border border-2 border-slate-200 flex flex-col rounded overflow-hidden mb-3">
    <div class="flex justify-between items-center py-2 px-3 bg-slate-200 text-slate-500">
        <label class="" for="{{ $uid }}">{{ $title }}</label>
        @if(!empty($value) && ($value != $initial))
            <button wire:click="removeFilter('{{$key}}')" class="text-slate-400 hover:text-red-400">clear</button>
        @endif
    </div>
    <div x-data="{model: @entangle('filters.' . $key).defer}"
         x-init="$nextTick(function () {
            flatpickr('#{{ $uid }}', {
                noCalendar: false,
                enableTime: false,
                dateFormat: '{{ $format }}',
             })
         })"
         wire:ignore
         class="relative">
        <input type="date" wire:model.defer="filters.{{$key}}" id="{{ $uid }}" placeholder="..."
               class="relative border-0 group block w-full dgDateFilter dgDateFilter{{ ucfirst(\Illuminate\Support\Str::camel($key)) }}" />
    </div>
</div>
