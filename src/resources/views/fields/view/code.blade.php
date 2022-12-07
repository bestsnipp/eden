<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5 rounded-md overflow-hidden"
         x-data
         x-init="
             $nextTick(function () {
                 CodeMirror.fromTextArea(document.getElementById('{{ $uid }}'), {
                    lineNumbers: true,
                    mode: 'javascript',
                    theme: 'monokai',
                    readOnly: 'nocursor'
                });
             })
        " wire:ignore>
        <textarea id="{{ $uid }}" cols="30" rows="10">{{ $value }}</textarea>
    </div>
</div>
