<div class="px-5 py-3">
    @include('eden::fields.label')
    <div class="flex items-start border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden dark:border-slate-700 dark:bg-slate-600">
        <div x-data="{
                model: @entangle('fields.' . $key){{ $alpineModelType }},
                codeEditor: null
            }"
             x-init="
             $nextTick(function () {
                 codeEditor = CodeMirror.fromTextArea(document.getElementById('{{ $uid }}'), {
                    lineNumbers: true,
                    mode: 'javascript',
                    theme: 'monokai'
                });
                codeEditor.on('blur', function (instance) {
                    model = instance.getValue();
                });
             })
            " wire:ignore class="flex flex-col w-full">
            <textarea id="{{ $uid }}" wire:model.{{ $wireModelType }}="fields.{{ $key }}" {!! $attributes !!}>{{ $value }}</textarea>
        </div>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
