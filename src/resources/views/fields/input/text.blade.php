<div class="px-5 py-3">
    @include('eden::fields.label')
    <input id="{{ $key }}" wire:model.lazy="fields.{{ $key }}" {!! $attributes !!}>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
