<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="flex items-center border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden dark:border-slate-700 dark:bg-slate-600">
        <span class="empty:hidden ml-2">{!! edenIcon($prefix) !!}</span>
        <div class="w-full flex" skip-wire:ignore>
            <select style="width: 100%;" id="{{ $uid }}" x-data="edenSelectField(@entangle('fields.' . $key){{ $alpineModelType }}, @js($searchFilterEnabled ?? true))" wire:model.{{ $alpineModelType }}="fields.{{ $key }}" {!! $attributes !!}>
                @foreach($options as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <span class="empty:hidden mr-2">{!! edenIcon($suffix) !!}</span>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
