<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="flex items-center border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden">
        <span class="empty:hidden ml-2">{!! edenIcon($prefix) !!}</span>
        <input id="{{ $uid }}" wire:model.defer="fields.{{ $key }}" {!! $attributes !!}>
        <span class="empty:hidden mr-2">{!! edenIcon($suffix) !!}</span>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
