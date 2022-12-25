<div class="{{ $footerStyle }}">
    <div>
        <button x-show="via.toString().toLowerCase() === 'backend'" type="button" wire:click.prevent="confirm()" class="{{ $confirmButtonStyle }}">
            <span>{{ $confirmButtonText }}</span>
            <span wire:loading.flex wire:target="confirm" class="absolute inset-0 bg-white/75 text-slate-500 items-center justify-center dark:bg-white/15">{!! edenIcon('dots-horizontal') !!}</span>
        </button>
        <button x-show="via.toString().toLowerCase() !== 'backend'"
                type="button"
                class="{{ $confirmButtonStyle }}"
                x-data="{payload: {file: null, files: []}}"
                x-on:click.prevent="
                    (selectionType === 'single' && selected.length > 0) ? payload = {file: _.head(selected)} : payload = {files: selected};
                    $dispatch(`media-manager-files-selected-${owner}`, payload);
                    showFromJs = false;
                ">
            <span>{{ $confirmButtonText }}</span>
        </button>
    </div>
    <button type="button" wire:click.prevent="dismiss()" class="{{ $cancelButtonStyle }}">
        <span>{{ $cancelButtonText }}</span>
        <span wire:loading.flex wire:target="dismiss" class="absolute inset-0 bg-white/75 text-slate-500 items-center justify-centerr dark:bg-white/15">{!! edenIcon('dots-horizontal') !!}</span>
    </button>
</div>
