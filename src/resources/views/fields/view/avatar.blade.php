<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5 gap-3 flex flex-wrap">
        @foreach($value as $file)
            <div class="border border-slate-100 rounded-full overflow-hidden p-0.5">
                <img src="{{ $file }}" alt="{{ $file }}" class="rounded-full aspect-square object-cover {{ $isMultiple ? '' : 'w-24' }}" />
            </div>
        @endforeach
    </div>
</div>
