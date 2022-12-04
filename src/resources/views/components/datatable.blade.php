<div
    data-dusk="dataTableContainer"
    class="dataTableContainer my-0 {{ $compWidth }} {{ $compHeight }}"
    @if($pooling) wire:poll.{{ $poolingInterval }}ms.visible @endif
    x-data="{showFilters: @entangle('showFilters').defer, selectedRows: @entangle('selectedRows').defer }">

    {{--  Show title and table links - otherwise hide  --}}
    @if(strlen($title) > 0 || count($operations) > 0)
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl text-primary-500">{{ $title }}</h1>
            <div class="flex gap-3 items-center">
                @foreach($operations as $operation)
                    {!! $operation->render() !!}
                @endforeach
            </div>
        </div>
    @endif

    <div class="">
        @include('eden::datatable.top')

        @if($isTableLayout) <div class="md:bg-white md:shadow-md -overflow-auto"><table class="table border-collapse w-full"> @endif

            @if($showHeader)
                {!! $this->header($records) !!}
            @endif

            @if(count($records) > 0)
                @if($this->isTableLayout) <tbody class="{{ $bodyStyle }}"> @else <div data-dusk="DataTableBody" class="{{ $bodyStyle }}"> @endif
                    @foreach($records as $record)
                        {!! $this->row($record, $records) !!}
                    @endforeach
                @if($this->isTableLayout) </tbody> @else </div> @endif
            @else
                {!! $this->emptyView() !!}
            @endif

        @if($isTableLayout) </table></div> @endif

        <div class="{{ $paginationStyle }}">
            {{ $records->links() }}
        </div>
    </div>

    @include('eden::datatable.filters')
</div>
