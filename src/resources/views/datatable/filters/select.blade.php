<div class="border border-2 border-slate-200 flex flex-col rounded overflow-hidden mb-3 dark:border-slate-500">
    <div class="flex justify-between items-center py-2 px-3 bg-slate-200 text-slate-500 dark:bg-slate-500 dark:text-slate-200">
        <label for="{{ $uid }}">{{ $title }}</label>
        @if(!empty($value) && $initial != $value)
            <button wire:click="removeFilter('{{$key}}', '{{$initial}}')" class="text-slate-400 hover:text-red-400">clear</button>
        @endif
    </div>
    <div x-data="{
                model: @entangle('filters.' . $key).defer
            }" x-init="
             $nextTick(function () {
                $('#{{ $uid }}')
                 .not('.select2-hidden-accessible')
                 .select2()
                 .on('select2:select', (event) => {
                    model = $(event.target).val();
                 })
                 .on('select2:unselect', (event) => {
                    model = $(event.target).val();
                 });
             });
             $watch('model', (value, oldValue) => refresh('#{{ $uid }}', 'select2'))"
         wire:ignore
         class="relative group block filterContainer">
        <select wire:model.defer="filters.{{$key}}" class="relative border-0 group block w-full py-2 px-3 dark:bg-slate-400 dark:text-slate-100" id="{{ $uid }}">
            @foreach($options as $optionKey => $optionValue)
                @if($isKeyValue)
                <option value="{{ $optionValue['value'] }}">{{ $optionValue['label'] }}</option>
                @else
                <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
