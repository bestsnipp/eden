<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="mt-2 flex items-start border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden">
        {{--<span class="empty:hidden mt-3 ml-2">{!! edenIcon($prefix) !!}</span>--}}
        <div x-data="{
                model: @entangle('fields.' . $key).defer,
            }"
             x-init="
             $nextTick(function () {
                 document.addEventListener('trix-change', function (event) {
                    model = event.target.value;
                })
             })
            " wire:ignore class="flex flex-col w-full">
            <textarea id="{{ $uid }}" wire:model.defer="fields.{{ $key }}" {!! $attributes !!} style="display: none !important;"></textarea>
            <trix-editor input="{{ $uid }}"></trix-editor>
        </div>
        {{--<span class="empty:hidden mt-3 mr-2">{!! edenIcon($suffix) !!}</span>--}}
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
