<div class="flex justify-end gap-2">
    @if(count($actions) <= config('eden.action_buttons_count', 3))
        @foreach($actions as $action)
            {!! $action->prepareRender(\Illuminate\Support\Arr::wrap($record), [])->render('button', $record, $buttonStyle, $iconSize) !!}
        @endforeach
    @else
    <div class="relative inline-flex" x-data="{isOpen: false}" @click.away="isOpen = false">
        <div @click="isOpen = !isOpen" class="relative px-2 py-1.5 bg-slate-50 rounded-md flex items-center cursor-pointer w-auto">{!! edenIcon('dots-vertical', 'scale-75') !!}</div>
        <div x-show="isOpen" x-transition.scale class="absolute z-50 border border-slate-50 mt-2 rounded-md shadow origin-top-right right-0 bg-white shadow-lg px-1 py-1 bg-white rounded-md w-44" style="display: none;">
            <ul class="list-inside">
                @foreach($actions as $action)
                    {!! $action->prepareRender(\Illuminate\Support\Arr::wrap($record), [])->render('list', $record, $buttonStyle, $iconSize) !!}
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
