<div class="px-5 py-3">
    @include('eden::fields.label')
    <textarea id="{{ $key }}" wire:model.lazy="fields.{{ $key }}" {!! $attributes !!}></textarea>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
