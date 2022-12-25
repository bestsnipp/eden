<div>
    @if($show)
    <div class="relative z-[99]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-90 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0" @if($closeOnOutsideClick) wire:click.self="dismiss()" @endif>
                <div class="{{ $style }} {{ $compWidth }}">

                    @if($header)
                    <div class="px-5 py-4 flex justify-between items-start">
                        <h3 class="text-lg font-medium text-slate-500 dark:text-slate-200" id="modal-title">{{ $title }}</h3>
                        <button type="button" wire:click.prevent="dismiss()" class="transition text-slate-300 hover:text-red-500">
                            {!! edenIcon('x-mark') !!}
                        </button>
                    </div>
                    @endif

                    @if($isEdenComponent)
                        <div class="{{ $contentStyle }}">@livewire($content->component, $content->params)</div>
                    @else
                        <div class="{{ $contentStyle }}">{!! $content !!}</div>
                    @endif

                    @if($footer)
                        {!! $viewForFooter !!}
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endif
</div>
