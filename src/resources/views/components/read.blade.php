<div class="{{ $compWidth }} {{ $compHeight }}">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl text-primary-500">{{ $title }}</h1>
    </div>

    <div class="mb-10 bg-white shadow sm:rounded-md divide-y divide-slate-100 text-slate-500">
        @foreach($fields as $field)
            @if($field->shouldShow())
                {!! $field->render('read') !!}
            @endif
        @endforeach
    </div>
</div>
