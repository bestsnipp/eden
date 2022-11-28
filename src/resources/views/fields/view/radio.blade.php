<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5">{{ isset($options[$value]) ? $options[$value] : $value }}</div>
</div>
