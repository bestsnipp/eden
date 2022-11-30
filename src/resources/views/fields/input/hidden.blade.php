<div class="px-5 flex w-full">
    <input id="{{ $uid }}" wire:model.{{ $wireModelType }}="fields.{{ $key }}" {!! $attributes !!}>
    @include('eden::fields.error')
</div>
