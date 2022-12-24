<div x-data="{selectedTab: 'library', allTabs: @js($tabs), selected: @entangle('selected').defer}" class="relative text-slate-500 dark:text-slate-300">
    <div class="flex justify-between">
        <div>
            <div x-cloak class="group p-0.5 rounded-lg inline-flex bg-slate-200">
                <template x-for="mediaTab in allTabs">
                    <button type="button"
                            x-on:click.prevent="selectedTab = mediaTab.type"
                            class="py-1.5 px-4 rounded-md flex items-center font-medium text-gray-600 hover:text-gray-900"
                            x-bind:class="{
                        'bg-white shadow-sm ring-1 ring-black ring-opacity-5': selectedTab === mediaTab.type,
                        'text-gray-600': selectedTab === mediaTab.type
                    }">
                        <span x-text="mediaTab.label"></span>
                    </button>
                </template>
            </div>
        </div>
        <div class="">
            <template x-if="selectedTab !== 'upload'">
                <button
                    type="button"
                    x-on:click.prevent="selectedTab = 'upload'"
                    class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-800 border border-transparent rounded-md text-slate-200 hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:bg-slate-700 dark:hover:bg-slate-500">
                    {!! edenIcon('upload', 'scale-75') !!}
                    <span>Upload Media</span>
                </button>
            </template>
        </div>
    </div>
    <div x-cloak class="py-6">
        @foreach($tabs as $tab)
            <div x-show="selectedTab === '{{ $tab['type'] }}'">
                @include($tab['view'])
            </div>
        @endforeach
    </div>
</div>
