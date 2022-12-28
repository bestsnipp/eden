<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="flex flex-col divide-y divide-slate-200 dark:divide-slate-600 border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden dark:border-slate-600 dark:bg-slate-500"
         x-data="{
            model: @entangle('fields.' . $key){{ $alpineModelType }},
            maxRecords: @js($maxRecords)
        }"
        x-init="$nextTick(() => {
            if (model.length <= 0) {
                //model.push({'key': '', 'value': ''});
            }
        })">
        <div class="flex flex-col md:flex-row bg-slate-100 text-slate-500 dark:text-slate-300 dark:bg-slate-600 text-sm">
            <div class="w-full grid grid-cols-1 md:grid-cols-3 divide-y md:divide-x md:divide-y-0 divide-slate-200 dark:divide-slate-600">
                <div class="col-span-1 py-2 px-2"><strong>{{ $keyLabel }}</strong></div>
                <div class="col-span-2 py-2 px-2"><strong>{{ $valueLabel }}</strong></div>
            </div>
            <button type="button" class="px-1 cursor-default hidden md:block opacity-0">{!! edenIcon('trash', 'scale-75') !!}</button>
        </div>
        <template x-for="(item, itemIndex) in model" key="`field-{{ $uid }}-${itemIndex}-${item.key}`">
            <div class="flex flex-col md:flex-row">
                <div class="grow grid grid-cols-1 md:grid-cols-3 divide-y md:divide-x md:divide-y-0 divide-slate-200 dark:divide-slate-600">
                    <div class="col-span-1"><textarea x-model="model[itemIndex].key" {!! $attributes !!} x-bind:class="['resize-none']"></textarea></div>
                    <div class="col-span-2"><textarea x-model="model[itemIndex].value" {!! $attributes !!}></textarea></div>
                </div>
                <button type="button" class="px-1" x-on:click.prevent="model.splice(itemIndex, 1)">{!! edenIcon('trash', 'scale-75') !!}</button>
            </div>
        </template>
        <template x-if="maxRecords > model.length">
            <button type="button"
                    class="py-2 px-2 bg-slate-100 text-slate-500 dark:text-slate-300 dark:bg-slate-600 text-sm cursor-pointer"
                    x-on:click.prevent="model.push({'key': '', 'value': ''})">
                {{ $actionText }}
            </button>
        </template>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
