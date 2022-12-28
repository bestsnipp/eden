<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        <div class="flex flex-col divide-y divide-slate-200 dark:divide-slate-600 border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden dark:border-slate-600 dark:bg-slate-500">
            <div class="flex flex-col md:flex-row bg-slate-100 text-slate-500 dark:text-slate-300 dark:bg-slate-600 text-sm">
                <div class="w-full grid grid-cols-1 md:grid-cols-3 divide-y md:divide-x md:divide-y-0 divide-slate-200 dark:divide-slate-600">
                    <div class="col-span-1 py-2 px-3"><strong>{{ $keyLabel }}</strong></div>
                    <div class="col-span-2 py-2 px-3"><strong>{{ $valueLabel }}</strong></div>
                </div>
            </div>
            @foreach($value as $index => $item)
                <div class="flex flex-col md:flex-row">
                    <div class="grow grid grid-cols-1 md:grid-cols-3 divide-y md:divide-x md:divide-y-0 divide-slate-200 dark:divide-slate-600">
                        <div class="col-span-1 py-2.5 px-3"><p>{{ $item['key'] ?? '' }}</p></div>
                        <div class="col-span-2 py-2.5 px-3"><p>{{ $item['value'] ?? '' }}</p></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
