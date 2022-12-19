<div class="px-0 my-1">
    <div x-data="{options: @js($chart), chart: null}" x-init="$nextTick(() => {
            chart = new ApexCharts($refs.chart, options);
            chart.render();
         })"
         @alpine-refresh.window="event => {
            if ($refs.parent === event.target) {
                chart.updateOptions(event.detail, true);
            }
         }"
         class="">
        <div class="flex justify-between items-center pl-4">
            <div wire:ignore class="max-h-28 overflow-auto grow" x-data x-eden-nice-scroll>
                <template x-if="null !== chart">
                    <ul>
                        <template x-for="(label, labelIndex) in chart.w.globals.labels">
                            <li class="text-sm my-1 text-slate-500 cursor-pointer dark:text-slate-300" @click="chart.toggleDataPointSelection(labelIndex);">
                                <span class="w-3 h-3 rounded-full inline-block" :style="{'background-color': chart.w.globals.colors[labelIndex]}"></span>
                                <span x-text="label"></span>
                                ( <span x-text="`${parseFloat(chart.w.globals.series[labelIndex]).toFixed(2)}`"></span> - <span x-text="`${parseFloat(chart.w.globals.seriesPercent[labelIndex]).toFixed(2)}%`"></span> )
                            </li>
                        </template>
                    </ul>
                </template>
            </div>
            <div class="w-2/5">
                <div x-ref="chart" class="min-h-24"></div>
            </div>
        </div>
    </div>
</div>
