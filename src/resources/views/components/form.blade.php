<div class="{{ $styleContainer }} {{ $compWidth }} {{ $compHeight }}">
    @if(!empty($title)) <h1 class="text-2xl text-primary-500 dark:text-primary-400 mb-4">{{ $title }}</h1> @endif

    <form wire:submit.prevent="submit" class="">
        <div class="py-3 bg-white dark:bg-slate-700 shadow dark:shadow-slate-800 sm:rounded-tl-md sm:rounded-tr-md">
            @foreach($formFields as $field)
                {!! $field->render('form') !!}
            @endforeach
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-slate-600 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <button type="submit"
                wire:loading.attr="disabled"
                wire:target="submit"
                class="{{ config('eden.button_style') }}">
                Save
            </button>
        </div>
    </form>
</div>
