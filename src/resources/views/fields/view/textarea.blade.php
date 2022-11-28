<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div x-data="{show: false}" class="w-full md:w-4/5">
        <template x-if="!show"><div class="prose prose-slate max-w-full">{!! $preview !!}</div></template>
        <template x-if="show"><div class="prose prose-slate max-w-full">{!! $value !!}</div></template>
        @if($hasExtra)
        <a href="#"
           @click.prevent="show = !show"
           class="bg-white w-auto h-auto border border-slate-300 rounded-md py-1.5 px-3 text-sm mt-3 inline-block">
            <span x-show="!show">Show Full Content</span>
            <span x-show="show">Hide Content</span>
        </a>
        @endif
    </div>
</div>
