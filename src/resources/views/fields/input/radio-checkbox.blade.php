<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="relative grid grid-cols-1 {{ $cols }}">
        @foreach($options as $optionKey => $optionValue)
            <div class="mt-1">
                <label class="inline-flex items-center gap-3">
                    <input value="{{ $optionKey }}" wire:model.{{ $wireModelType }}="fields.{{$key}}" name="{{$key}}" {!! $attributes !!}>
                    <div class="inline-flex gap-1">
                        <span class="empty:hidden">{!! edenIcon($prefix) !!}</span>
                        <span>{{ $optionValue }}</span>
                        <span class="empty:hidden">{!! edenIcon($suffix) !!}</span>
                    </div>
                </label>
            </div>
        @endforeach
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
