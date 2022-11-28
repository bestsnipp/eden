<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="block w-full md:w-4/5">
        <a
            class="text-primary-500"
            target="_blank"
            href="mailto:{!! is_array($value) ? implode(',', $value) : $value !!}">
            {!! is_array($value) ? implode(',', $value) : $value !!}
        </a>
    </div>
</div>
