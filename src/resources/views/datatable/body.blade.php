@if($this->isTableLayout) <tbody> @else <div data-dusk="DataTableBody"> @endif
@foreach($data as $row)
    {!! $this->rowView($row)->render() ?? '' !!}
@endforeach
@if($this->isTableLayout) </tbody> @else </div> @endif
