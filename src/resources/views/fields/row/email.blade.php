<div class="w-full">
    <a
        class="text-primary-500 dark:text-primary-300"
        target="_blank"
        href="mailto:{!! is_array($value) ? implode(',', $value) : $value !!}">
        {!! is_array($value) ? implode(',', $value) : $value !!}
    </a>
</div>
