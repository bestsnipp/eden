<div class="px-5 py-3">
    @include('eden::fields.label')
    <div
        x-data="{
            model: @entangle('fields.' . $key).defer,
            isMultiple: @JSON(isset($attributes['multiple'])),
        }"
        x-init="iniliatizeSelect2()"
        wire:ignore>
        <select id="{{ $key }}" wire:model.lazy="fields.{{ $key }}" {!! $attributes !!}>
            @foreach($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
