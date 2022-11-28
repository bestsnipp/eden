<div class="px-5 py-3">
    @include('eden::fields.label')
    <div x-data="{type: 'password'}" class="flex items-center border border-slate-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 rounded-md shadow-sm w-full overflow-hidden">
        <span class="empty:hidden ml-2">{!! edenIcon($prefix) !!}</span>
        <input id="{{ $uid }}" wire:model.lazy="fields.{{ $key }}" x-bind:type="type" {!! $attributes !!}>
        <span class="empty:hidden mr-2">{!! edenIcon($suffix) !!}</span>
        @if($viewable)
        <button type="button" x-on:click="type === 'password' ? type='text' : type='password'" class="px-2 focus:outline-0 focus-visible:outline-0">
            <span x-cloak x-show="type === 'password'">{!! edenIcon('eye') !!}</span>
            <span x-cloak x-show="type !== 'password'">{!! edenIcon('eye-off') !!}</span>
        </button>
        @endif
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')
</div>
