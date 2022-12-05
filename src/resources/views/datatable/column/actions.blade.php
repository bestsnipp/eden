<div class="flex justify-end gap-2">
    @if(count($actions) <= config('eden.action_buttons_count', 3))
        @foreach($actions as $action)
            @if($action instanceof \Dgharami\Eden\Components\DataTable\Actions\StaticAction)
                {!! $action->setOwner($this)->prepare([$record], [])->render('button', $action, $record, $buttonStyle, $iconSize) !!}
            @else
                {!! $action->render('button', $action, $record, $buttonStyle, $iconSize) !!}
            @endif
        @endforeach
    @else
    <div class="relative inline-flex" x-data="{isOpen: false}" @click.away="isOpen = false">
        <div @click="isOpen = !isOpen" class="relative px-2 py-1.5 bg-slate-50 rounded-md flex items-center cursor-pointer w-auto">{!! edenIcon('dots-vertical', 'scale-75') !!}</div>
        <div x-show="isOpen" x-transition.scale class="absolute z-50 border border-slate-50 mt-2 rounded-md shadow origin-top-right right-0 bg-white shadow-lg px-1 py-1 bg-white rounded-md w-44" style="display: none;">
            <ul class="list-inside">
                @foreach($actions as $action)
                    @if($action instanceof \Dgharami\Eden\Components\DataTable\Actions\StaticAction)
                        {!! $action->setOwner($this)->prepare([$record], [])->render('list', $action, $record, $buttonStyle, $iconSize) !!}
                    @else
                        {!! $action->render('list', $action, $record, $buttonStyle, $iconSize) !!}
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
