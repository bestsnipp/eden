<div class="px-4 my-4">
    <div class="flex justify-between items-end">
        <h2 class="font-bold text-2xl text-slate-600 mb-4 mt-2 flex dark:text-slate-200">{!! $valueLabel !!}</h2>
        <h4 class="font-medium text-slate-500 dark:text-slate-300">{!! $targetLabel !!}</h4>
    </div>
    <div data-dusk="progress" class="py-3">
        @if($avoid)
            <div class="w-full bg-green-300 rounded-full">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full h-2" style="width: {{ min($percentage, 100) }}%"></div>
            </div>
        @else
            <div class="w-full bg-slate-200 rounded-full dark:bg-slate-400">
                <div class="bg-green-500 text-white rounded-full h-2" style="width: {{ min($percentage, 100) }}%"></div>
            </div>
        @endif
    </div>
</div>
