<div class="{{ $footerStyle }}">
    <button type="button" wire:click.prevent="confirm()" class="{{ $confirmButtonStyle }}">
        <span>{{ $confirmButtonText }}</span>
        <span wire:loading.flex wire:target="confirm" class="absolute inset-0 bg-white/75 text-slate-500 items-center justify-center dark:bg-white/15">{!! edenIcon('dots-horizontal') !!}</span>
    </button>
    <button type="button" wire:click.prevent="dismiss()" class="{{ $cancelButtonStyle }}">
        <span>{{ $cancelButtonText }}</span>
        <span wire:loading.flex wire:target="dismiss" class="absolute inset-0 bg-white/75 text-slate-500 items-center justify-centerr dark:bg-white/15">{!! edenIcon('dots-horizontal') !!}</span>
    </button>
</div>
