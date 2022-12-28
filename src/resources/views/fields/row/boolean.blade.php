<div class="inline-flex items-center gap-2">
    <label for="{{ $uid }}" class="inline-flex relative items-center cursor-pointer gap-3">
        <input id="{{ $uid }}" class="sr-only peer" type="checkbox" @if($value) checked="checked" @endif disabled />
        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-slate-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-500 peer-checked:bg-primary-600"></div>
    </label>
</div>
