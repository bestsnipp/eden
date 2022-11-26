<div class="px-5 flex w-full">
    <input id="{{ $uid }}" wire:model.defer="fields.{{ $key }}" {!! $attributes !!}>
    @include('eden::fields.error')
</div>
