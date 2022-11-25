<div class="px-5 py-3">
    @include('eden::widgets.fields.base.label')
    <div x-data="{
                model: @entangle('fields.' . $key).defer,
            }"
         x-init="
             $nextTick(function () {
                 document.addEventListener('trix-change', function (event) {
                    model = event.target.value;
                })
             })
         " wire:ignore class="mt-2">
        <textarea id="{{ $key }}" wire:model.defer="fields.{{ $key }}" {!! $attributes !!} style="display: none !important;"></textarea>
        <trix-editor input="{{ $key }}"></trix-editor>
    </div>
    @include('eden::widgets.fields.base.error')
    @include('eden::widgets.fields.base.help')
</div>
