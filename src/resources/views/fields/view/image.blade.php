<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($value as $file)
            <div class="border border-slate-100 rounded-lg overflow-hidden p-0.5">
                <div class="flex justify-between items-center bg-slate-100">
                    @if($fileLabelEnabled) <h3 class="truncate px-2">{{ basename($file) ?? $file }}</h3> @endif
                    @if($largePreviewEnabled) <a target="_blank" href="{{ $file['url'] ?? $file }}" class="py-1 px-2 bg-slate-100 rounded hover:bg-slate-100 transition-all">{!! edenIcon('external-link', 'scale-50') !!}</a> @endif
                    @if($downloadEnabled) <a download href="{{ $file['url'] ?? $file }}" class="py-1 px-2 bg-slate-100 rounded hover:bg-slate-100 transition-all">{!! edenIcon('arrow-down-tray') !!}</a> @endif
                </div>
                <img src="{{ $file }}" alt="{{ $file }}" class="" />
            </div>
        @endforeach
        </div>
    </div>
</div>
