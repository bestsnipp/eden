<div class="px-5 py-4 flex justify-between items-start">
    <h3 class="text-lg font-medium text-slate-500 dark:text-slate-200" id="modal-title">{{ $title }}</h3>
    <button type="button" @if($isJsInteractionEnabled) x-on:click.prevent="showFromJs = false" @else wire:click.prevent="dismiss()" @endif class="transition text-slate-300 hover:text-red-500">
        {!! edenIcon('x-mark') !!}
    </button>
</div>
