{{--    Search and Filters    --}}
<div class="{{ $headerStyle }}">
    <div class="flex flex-col md:flex-row justify-between items-center">

        {{--   Search - Show or Hide   --}}
        @if($isSearchable)
            <div class="relative rounded-md shadow-sm group block md:inline-block w-full md:w-auto mb-3 md:mb-0">
                <input wire:model.debounce.500ms="searchQuery" type="text" class="focus:ring-primary-500 focus:border-primary-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-slate-500 dark:border-slate-600 dark:text-slate-300" placeholder="Search ...">
                <div class="absolute inset-y-0 right-0 flex items-center px-3">
                    @if(!empty($searchQuery))
                        <button class="mr-3 hover:text-red-500" wire:click="$set('searchQuery', '')">{!! edenIcon('x-mark') !!}</button>
                    @endif
                    <button class="scale-75">{!! edenIcon('search') !!}</button>
                </div>
            </div>
        @else
            <div>&nbsp;</div>
        @endif

        <div class="flex items-center justify-end gap-2">

            {{--   Bulk Actions   --}}
            <div
                x-show="selectedRows.length > 0"
                x-cloak
                x-transition
                x-data="{isOpen: false}" @click.away="isOpen = false"
                class="relative">
                <button @click="isOpen = !isOpen" class="relative py-1.5 px-4 rounded-md flex justify-between items-center font-medium bg-white shadow-sm border border-gray-300 text-slate-400 w-full md:w-auto dark:bg-slate-500 dark:border-slate-600 dark:text-slate-300">
                    <span class="mr-3">Actions</span>
                    {!! edenIcon('chevron-down', 'scale-75') !!}
                </button>
                <div x-show="isOpen" x-transition.scale class="absolute z-50 border border-slate-50 mt-2 rounded-md shadow origin-top-right right-0 bg-white shadow-lg px-1 py-1 bg-white rounded-md w-44" style="display: none;">
                    <ul class="list-inside">
                        @foreach($actions as $action)
                            <li @click="isOpen = false" wire:click="applyBulkAction('{{ $action->getKey() }}')" class="py-2 px-3 rounded cursor-pointer transition hover:bg-indigo-50 text-slate-500">
                                <div wire:loading.remove wire:target="applyBulkAction('{{ $action->getKey() }}')" class="flex items-center gap-3">
                                    @if(!is_null($action->icon))
                                        {!! edenIcon($action->icon, 'scale-75') !!}
                                    @endif
                                    <span class="text-left">{{ $action->getTitle() }}</span>
                                </div>
                                <div wire:loading.flex wire:target="applyBulkAction('{{ $action->getKey() }}')" class="gap-3">
                                    {!! edenIcon('dots-horizontal', 'scale-75') !!}
                                    <span class="text-left">Please Wait ...</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{--   Filters   --}}
            <button
                x-on:click="showFilters = true"
                class="relative py-1.5 px-4 rounded-md flex justify-between items-center font-medium bg-white shadow-sm border border-gray-300 text-slate-400 w-full md:w-auto dark:bg-slate-500 dark:border-slate-600 dark:text-slate-300">
                <span class="mr-3">Filters</span>
                {!! edenIcon('funnel', 'scale-95') !!}
                @if($appliedFilters)
                    <span class="absolute top-0 right-0 transform -translate-y-1/2 w-3.5 h-3.5 bg-indigo-400 rounded-full"></span>
                @endif
            </button>
        </div>
    </div>
</div>

{{--   Loader for Data Table   --}}
<div class="relative bg-white h-0">
    <div wire:loading.flex class="animate-pulse">
        <div class="flex-1">
            <div class="h-0.5 bg-primary-50 dark:bg-primary-400"></div>
        </div>
    </div>
</div>

{{--   Show Applied Filters   --}}
@if($appliedFilters)
    <div class="{{ $appliedFilterStyle }}">
        <h3 class="inline-block md:hidden w-full text-xl mb-2">Applied Filters</h3>
        @foreach($appliedFilters as $filterActive)
            <div class="inline-flex items-center bg-slate-100 py-2 px-3 rounded text-slate-500 dark:text-slate-300 dark:bg-slate-500">
                <strong class="font-normal text-slate-500 dark:text-slate-300">{{ $filterActive['title'] }}</strong>
                @if(is_array($filterActive['value']))
                    <span class="font-bold mx-3">{{ implode(', ', $filterActive['value']) }}</span>
                @else
                    <span class="font-bold mx-3">{{ $filterActive['value'] }}</span>
                @endif
                @if($filterActive['canRemove'])
                    <button type="button" wire:click="removeFilter('{{$filterActive['key']}}', '')" class="flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-300 hover:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach
    </div>
@endif
