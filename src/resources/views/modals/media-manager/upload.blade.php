<div class="border-2 border-slate-200 border-dashed rounded-lg h-96 bg-slate-50 text-center p-6 flex flex-col justify-center items-center gap-2">
    <h3 class="text-xl font-medium text-slate-700">Drop Files to Upload</h3>
    <p>or</p>
    <div>
        <input type="file" id="mediaFileUploader" multiple style="display: none;" />
        <label for="mediaFileUploader" class="cursor-pointer inline-flex items-center gap-2 px-4 py-1.5 bg-slate-800 border border-transparent rounded-md text-slate-200 hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:bg-slate-700 dark:hover:bg-slate-500">
            {!! edenIcon('plus-sm') !!} Select Files
        </label>
    </div>
</div>
