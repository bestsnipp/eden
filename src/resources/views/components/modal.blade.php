<div @if($isJsInteractionEnabled) x-data="{isVisible: @js($show), showFromJs: false}" x-cloak @endif>
    @if($show || $isJsInteractionEnabled)
    <div class="relative z-[99] @if(!$show) hidden @endif" aria-labelledby="modal-title" role="dialog" aria-modal="true" @if($isJsInteractionEnabled) x-show="isVisible && showFromJs" @endif>
        <div class="fixed inset-0 bg-slate-900 bg-opacity-90 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0" @if($closeOnOutsideClick) @if($isJsInteractionEnabled) x-on:click.self="showFromJs = false" @else wire:click.prevent="dismiss()" @endif @endif>
                <div class="{{ $style }} {{ $compWidth }}" {!! \Illuminate\Support\Arr::toHtmlAttribute($bodyAttrs) !!}>

                    @if($header) {!! $viewForHeader !!} @endif

                    @if($isEdenComponent)
                        <div class="{{ $contentStyle }}">@livewire($content->component, $content->params)</div>
                    @else
                        <div class="{{ $contentStyle }}">{!! $content !!}</div>
                    @endif

                    @if($footer) {!! $viewForFooter !!} @endif

                </div>
            </div>
        </div>
    </div>
    @endif
</div>
