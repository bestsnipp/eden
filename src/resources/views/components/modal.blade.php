<div>
    @if($show)
    <div class="relative z-[99]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-90 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0" @if($closeOnOutsideClick) wire:click.self="dismiss()" @endif>
                <div class="{{ $style }} {{ $compWidth }}">

                    @if($header)
                    <div class="px-4 py-4 sm:px-6 flex justify-between items-start">
                        <h3 class="text-lg font-medium text-slate-500 dark:text-slate-200" id="modal-title">{{ $title }}</h3>
                        <button type="button" wire:click.prevent="dismiss()" class="transition text-slate-300 hover:text-red-500">
                            {!! edenIcon('x-mark') !!}
                        </button>
                    </div>
                    @endif

                    @if($isEdenComponent)
                        <div class="">@livewire($content->component, $content->params)</div>
                    @else
                        <div>{!! $content !!}</div>
                    @endif

                    @if($footer)
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
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endif
</div>
