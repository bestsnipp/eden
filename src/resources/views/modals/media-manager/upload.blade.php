<div class="border-2 border-dashed rounded-lg h-96 text-center p-6 flex flex-col justify-center items-center gap-2 relative"
     x-data="{ isDropping: false, isUploading: false, progress: 0 }"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="isUploading = false"
     x-on:livewire-upload-error="isUploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"

     x-on:drag="e => e.preventDefault()"
     x-on:dragstart="e => {e.preventDefault(); isDropping = true}"
     x-on:dragend="e => {e.preventDefault(); isDropping = false}"
     x-on:dragover="e => {e.preventDefault(); isDropping = true}"
     x-on:dragenter="e => {e.preventDefault(); isDropping = true}"
     x-on:dragleave="e => {e.preventDefault(); isDropping = false}"
     x-on:drop="e => {
        e.preventDefault();
        isDropping = false;
        isUploading = true;
        progress = 0;
        $wire.uploadMultiple('fileupload', e.dataTransfer.files, () => isUploading = false, () => isUploading = false, (event) => progress = event.detail.progress)
    }"
    x-bind:class="{
        'border-slate-200 bg-slate-50 dark:bg-slate-700 dark:border-slate-500': !isDropping,
        'border-primary-200 bg-primary-200 dark:bg-primary-700 dark:border-primary-500': isDropping
    }">
    <h3 class="text-xl font-medium text-slate-700 dark:text-slate-300">Drop Files to Upload</h3>
    <p>or</p>
    <div>
        <input type="file" wire:model="fileupload" multiple style="display: none;" id="mediaFileUploader" x-ref="mediaUploadedFileField" x-on:change="progress = 0" />
        <label for="mediaFileUploader" class="cursor-pointer inline-flex items-center gap-2 px-4 py-1.5 bg-slate-800 border border-transparent rounded-md text-slate-200 hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:bg-slate-600 dark:hover:bg-slate-500">
            {!! edenIcon('plus-sm') !!} Select Files
        </label>
    </div>

    <div x-show="isUploading" class="absolute bg-white w-full h-full flex justify-center items-center top-0 left-0 rounded-md">
        <div class="relative w-2/3">
            <label class="block mb-1 text-xs text-center text-slate-500">Uploading ...</label>
            <progress max="100" x-bind:value="progress" class="block w-full overflow-hidden rounded bg-slate-200 [&::-webkit-progress-bar]:bg-slate-200 [&::-webkit-progress-value]:bg-emerald-500 [&::-moz-progress-bar]:bg-emerald-500"> 40% </progress>
        </div>
    </div>
</div>
