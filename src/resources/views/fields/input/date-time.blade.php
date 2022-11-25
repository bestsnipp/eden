<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="flex items-center border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden">
        <span class="empty:hidden ml-2">{!! edenIcon($prefix) !!}</span>
        <div class="flex grow" x-data="{model: @entangle('fields.' . $key).defer}"
             x-init="$nextTick(function () {
                flatpickr('#{{ $key }}', {
                    noCalendar: @JSON(!$isDatePicker),
                    enableTime: @JSON($isTimePicker),
                    dateFormat: '{{ $format }}',
                    defaultDate: new Date('{{ $value }}'),
                    clickOpens: @JSON(!isset($meta['readonly'])),
                 })
             })"
             wire:ignore>
            <input id="{{ $key }}" wire:model.defer="fields.{{ $key }}" {!! $attributes !!}>
        </div>
        <span class="empty:hidden mr-2">{!! edenIcon($suffix) !!}</span>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
