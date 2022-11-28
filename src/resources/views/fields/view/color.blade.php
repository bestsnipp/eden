<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">
        <div class="inline-flex items-center gap-2">
            <span class="w-6 h-6 rounded-md border border-white" style="background-color: {{ $value }};"></span>
            <span>{{ $value }}</span>
        </div>
    </div>
</div>
