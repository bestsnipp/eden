@if($this->isTableLayout) <thead class="text-left hidden md:table-header-group"><tr> @else <div class="flex shadow-md"> @endif

    @foreach($fields as $field)
        @if($this->isTableLayout)
            <th class="py-2 px-2 bg-slate-100 text-slate-500 text-sm @if($field->isSortable()) cursor-pointer @endif"
                @if($field->isSortable()) wire:click="sortField('{{ $field->getKey() }}')" @endif>
        @else
            <div class="grow py-2 px-2 bg-slate-100 text-slate-500 text-sm @if($field->isSortable()) cursor-pointer @endif"
                @if($field->isSortable()) wire:click="sortField('{{ $field->getKey() }}')" @endif  style="width: {{ 100/count($fields) }}%;">
        @endif

        <div class="flex gap-2 items-center {{ ['left' => 'justify-start', 'right' => 'justify-end', 'center' => 'justify-center'][$field->textAlign] }}">
            <strong>{!! $field->render('table-header') !!}</strong>

            @if($field->isSortable())
                @if($field->getOrderBy() == 'asc')
                    {!! edenIcon('chevron-up', 'scale-50') !!}
                @elseif($field->getOrderBy() == 'desc')
                    {!! edenIcon('chevron-down', 'scale-50') !!}
                @else
                    <span class="text-slate-400 scale-75">{!! edenIcon('chevron-up-down') !!}</span>
                @endif
            @endif
        </div>

        @if($this->isTableLayout)
            </th>
        @else
            </div>
        @endif
    @endforeach

@if($this->isTableLayout) </tr></thead> @else </div> @endif
