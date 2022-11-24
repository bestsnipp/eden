<div class="px-5">
    <input id="{{ $key }}" wire:model.defer="fields.{{ $key }}" {!! $attributes !!}>
    @include('eden::fields.error')
</div>
