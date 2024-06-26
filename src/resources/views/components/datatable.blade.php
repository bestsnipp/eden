<div
    data-dusk="dataTableContainer"
    class="dataTableContainer my-0 {{ $containerClass }} {{ $compWidth }} {{ $compHeight }}"
    @if($pooling) wire:poll.{{ $poolingInterval }}ms.visible @endif
    x-data="{showFilters: @entangle('showFilters').defer, selectedRows: @entangle('selectedRows').defer }">

    {{--  Show title and table links - otherwise hide  --}}
    @if(strlen($title) > 0 || count($operations) > 0)
        <div class="flex justify-between items-center mb-4 {{ $containerTopAreaClass }}">
            <h1 class="text-2xl text-primary-500 dark:text-primary-400">{{ $title }}</h1>
            <div class="flex gap-3 items-center">
                @foreach($operations as $operation)
                    @if($operation instanceof \BestSnipp\Eden\RenderProviders\RenderProvider)
                        @livewire($operation->component, $operation->params)
                    @else
                        {!! $operation->render() !!}
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="{{ $wrapperClass }}">
        @includeWhen($shouldShowCardHeader, 'eden::datatable.top')

        @if($isTableLayout) <div class="md:bg-white md:shadow-md overflow-x-auto"><table class="table border-collapse w-full {{ $tableClass }}"> @endif

            @if($showHeader)
                {!! $this->header($records) !!}
            @endif

            @if(count($records) > 0)
                @if($this->isTableLayout) <tbody class="{{ $bodyStyle }}" {!! \Illuminate\Support\Arr::toHtmlAttribute($bodyAttrs) !!}> @else <div data-dusk="DataTableBody" class="{{ $bodyStyle }}" {!! \Illuminate\Support\Arr::toHtmlAttribute($bodyAttrs) !!}> @endif
                    @foreach($records as $record)
                        {!! $this->row($record, $records) !!}
                    @endforeach
                @if($this->isTableLayout) </tbody> @else </div> @endif
            @else
                {!! $this->emptyView() !!}
            @endif

        @if($isTableLayout) </table></div> @endif

        @if($shouldShowPagination)
            <div class="{{ $paginationStyle }}">{{ $records->links() }}</div>
        @endif
</div>

    @include('eden::datatable.filters')
</div>
