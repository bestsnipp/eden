<div class="px-5 py-3">
    @include('eden::fields.label')
    <div
        x-data="{ isUploading: false, progress: 0 }"
        x-on:livewire-upload-start="isUploading = true"
        x-on:livewire-upload-finish="isUploading = false"
        x-on:livewire-upload-error="isUploading = false"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        class="relative border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm p-1 block w-full dark:bg-slate-600 dark:border-slate-700 focus-within:border-indigo-700 dark:text-slate-300">
        <input id="{{ $uid }}" wire:model="fields.{{ $key }}" {!! $attributes !!} >

        <label class="flex items-center" for="{{ $uid }}">
            <label for="{{ $uid }}" class="whitespace-nowrap bg-white py-2 px-3 border border-gray-200 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-700 dark:shadow-slate-800 dark:text-slate-300">Choose File</label>
            <span class="empty:hidden mx-2">{!! edenIcon($prefix) !!}</span>
            <span class="text-sm text-gray-500 dark:text-slate-300 ml-3 truncate grow">
                @if (empty($displayValues))
                    No file chosen
                @else
                    @if(is_array($displayValues))
                        {{ count($displayValues) }} {{ \Illuminate\Support\Str::pluralStudly('File', count($displayValues)) }} Selected
                    @else
                        {{ $displayValues }}
                    @endif
                @endif
            </span>

            @if($value && !(isset($meta['disabled']) || isset($meta['readonly'])))
                <button type="button" class="text-slate-400 lowercase px-2" wire:click='clearUploadedFiles("fields.{{$key}}", @JSON(isset($meta['multiple']) ? [] : ""))'>Clear</button>
            @endif
            <span class="empty:hidden mr-2">{!! edenIcon($suffix) !!}</span>
        </label>
        <div x-show="isUploading && progress > 0" class="w-full h-full rounded-md -mt-1 absolute left-0 top-1 overflow-hidden" style="display: none;">
            <div class="bg-indigo-600 h-full opacity-10" :style="{width: progress + '%'}"></div>
        </div>
    </div>
    @include('eden::fields.error')
    @include('eden::fields.help')

    <div class="{{ $isMultiple ? 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4' : 'grid-cols-1' }} grid mt-3 gap-4">
        @if(is_array($displayValues))
            @foreach($displayValues as $fileIndex => $file)
                <div class="relative">
                    <button type="button"
                            class="bg-white/50 dark:bg-slate-600/50 hover:bg-white hover:text-red-500 dark:hover:bg-slate-700 dark:hover:text-red-300 absolute right-1 top-1 rounded-full p-1"
                        @if($isMultiple)
                            wire:click='clearUploadedSingleFile("fields.{{$key}}", "{{ $fileIndex }}")'
                        @else
                            wire:click='clearUploadedFiles("fields.{{$key}}", "")'
                        @endif
                    >{!! edenIcon('x-mark') !!}</button>
                    <img src="{{ $folder . $file }}" alt="{{ $folder . $file }}" class="rounded-md aspect-square object-cover {{ $isMultiple ? '' : 'w-full h-60' }}" />
                </div>
            @endforeach
        @endif
    </div>
</div>
