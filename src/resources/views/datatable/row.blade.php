@if($this->isTableLayout) <tr class="block md:table-row bg-white shadow-md my-4 rounded-md md:bg-transparent md:shadow-none md:my-0 md:rounded-none"> @else <div class="flex"> @endif

@foreach($fields as $field)
    @if($this->isTableLayout)
        <td
            data-label="{{ $field->getTitle() }}"
            class="before:content-[attr(data-label)] before:text-slate-700 before:font-bold md:before:content-none
                   flex justify-between md:table-cell py-4 px-5 md:py-4 md:px-2
                   text-slate-500 border-b border-b-slate-100">
    @else
        <div class="grow py-3 px-2 bg-white" style="width: {{ 100/count($fields) }}%;">
    @endif

        {!! $field->record($record)->render('table-row') !!}

    @if($this->isTableLayout) </td> @else </div> @endif
@endforeach

@if($this->isTableLayout) </tr> @else </div> @endif
