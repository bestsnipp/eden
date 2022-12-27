<div class="relative z-10" style="display: none;" x-show="showFilters" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-500 bg-opacity-25 md:bg-transparent transition-opacity" @click="showFilters = false"></div>

    <div -class="fixed inset-0 overflow-hidden">
        <div -class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto relative w-screen max-w-sm">
                    <div class="flex h-full flex-col overflow-y-auto bg-white dark:bg-slate-600 pt-6 shadow-xl">
                        <div class="px-4 sm:px-6 flex justify-between items-center">
                            <h2 class="text-xl text-slate-500 dark:text-slate-300">Filters</h2>

                            <button @click="showFilters = false" type="button" class="rounded-md text-gray-300 hover:text-red-400 focus:outline-none focus:ring-2 focus:ring-white">
                                <span class="sr-only">Close panel</span>
                                {!! edenIcon('x-mark') !!}
                            </button>
                        </div>
                        <div class="relative mt-6 flex flex-col px-4 sm:px-6 pb-16">
                            @foreach($allFilters as $filter)
                                {!! $filter->render() !!}
                            @endforeach
                            @if($showRowsPerPageFilter)
                                @include('eden::datatable.rows-per-page', [
                                    'key' => 'rowsPerPage',
                                    'title' => 'Rows Per Page',
                                    'options' => $rowsPerPageOptions,
                                    'value' => $rowsPerPage,
                                    'initial' => $initialRowsPerPage
                                ])
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3 px-5 gap-2 bg-white absolute bottom-0 w-full dark:bg-slate-600">
                            <button
                                @click="showFilters = false"
                                class="py-1.5 px-4 rounded-md flex justify-center items-center font-medium bg-slate-100 text-slate-400 w-full md:w-auto dark:bg-slate-500 dark:text-slate-300">
                                <span>Close</span>
                            </button>
                            <button
                                wire:click="applyFilters"
                                class="py-1.5 px-4 rounded-md flex justify-center items-center font-medium bg-primary-500 text-white w-full md:w-auto">
                                <span>Apply Filters</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
