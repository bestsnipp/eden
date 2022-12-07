<div class="px-5 py-3 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-1/5">
        <strong>{{ $title }}</strong>
    </div>
    <div class="w-full md:w-4/5 flex items-center" x-data="{type: 'password'}">
        <input id="{{ $uid }}" value="{{ is_array($value) ? implode(',', $value) : $value }}" x-bind:type="type" class="border-0 p-0">
        @if($viewable)
            <button type="button" x-on:click="type === 'password' ? type='text' : type='password'" class="px-2 focus:outline-0 focus-visible:outline-0">
                <span x-cloak x-show="type === 'password'">{!! edenIcon('eye') !!}</span>
                <span x-cloak x-show="type !== 'password'">{!! edenIcon('eye-off') !!}</span>
            </button>
        @endif
    </div>
</div>
