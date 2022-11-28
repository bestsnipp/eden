<div class="{{ $styleContainer }} {{ $compWidth }} {{ $compHeight }}">
    <h1 class="text-2xl text-primary-500 mb-4">{{ $title }}</h1>

    <form wire:submit.prevent="submit" class="">
        <div class="py-3 bg-white shadow sm:rounded-tl-md sm:rounded-tr-md">
            @foreach($formFields as $field)
                @if($field->shouldShow())
                    {!! $field->render('form') !!}
                @endif
            @endforeach
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <div x-data="{ shown: false, timeout: null }" x-init="$wire.on('formSaved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);  })" x-show.transition.out.opacity.duration.1500ms="shown" x-transition:leave.opacity.duration.1500ms="" style="display: none;" class="text-sm text-gray-600 mr-3">
                Saved.
            </div>
            <div x-data="{ showError: false, showErrorTimeout: null, showErrorMsg: '' }" x-init="$wire.on('autoRecordFailed', (errorMsg) => { showErrorMsg = errorMsg; clearTimeout(showErrorTimeout); showError = true; showErrorTimeout = setTimeout(() => { showError = false }, 5000);  })" x-show.transition.out.opacity.duration.1500ms="showError" x-transition:leave.opacity.duration.1500ms="" style="display: none;" class="text-sm text-gray-600 mr-3">
                <span x-html="showErrorMsg"></span>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" wire:loading.attr="disabled" wire:target="submit">
                Save
            </button>
        </div>
    </form>
</div>
