<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        @foreach($value as $file)
            <div class="py-2 px-3 mb-2 border border-slate-200 rounded-md flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    {{ $file['name'] ?? $file }}
                </span>
                @if($downloadEnabled) <a download href="{{ $file['url'] ?? $file }}" class="py-1 px-2 bg-slate-50 rounded hover:bg-slate-100 transition-all">{!! edenIcon('arrow-down-tray') !!}</a> @endif
            </div>
        @endforeach
    </div>
</div>
