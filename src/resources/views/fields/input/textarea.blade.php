<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="flex items-start border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden">
        <span class="empty:hidden mt-2 ml-2">{!! edenIcon($prefix) !!}</span>
        <textarea id="{{ $key }}" wire:model.lazy="fields.{{ $key }}" {!! $attributes !!}></textarea>
        <span class="empty:hidden mt-2 mr-2">{!! edenIcon($suffix) !!}</span>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
