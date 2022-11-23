<div class="px-0 mt-2">
    <div x-data="{options: @js($chart), chart: null}" x-init="$nextTick(() => {
            chart = new ApexCharts($refs.chart, options);
            chart.render();
            // alert('Chart Initialized');
         })"
         @alpine-refresh.window="event => {
            if ($refs.parent === event.target) {
                chart.updateOptions(event.detail, true);
            }
         }"
         class="">
        @if($showLatest) <h2 class="font-bold text-2xl text-slate-600 mt-2 mb-1 px-4 flex">{!! $valueLabel !!}</h2> @endif
        <div x-ref="chart" class="min-h-24"></div>
    </div>
</div>
