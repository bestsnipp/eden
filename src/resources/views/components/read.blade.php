<div class="{{ $compWidth }} {{ $compHeight }}">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl text-primary-500">{{ $title }}</h1>
        <div class="flex gap-3 items-center">
            @foreach($operations as $operation)
                {!! $operation->render() !!}
            @endforeach

            {!! $actionButtons->render('read') !!}
        </div>
    </div>

    <div class="mb-10 bg-white shadow sm:rounded-md divide-y divide-slate-100 text-slate-500">
        @foreach($fields as $field)
            {!! $field->render('read') !!}
        @endforeach
    </div>
</div>
