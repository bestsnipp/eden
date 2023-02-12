<div class="{{ $compWidth }} {{ $compHeight }}">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl text-primary-500 dark:text-primary-400">{{ $title }}</h1>
        <div class="flex gap-3 items-center">
            @foreach($operations as $operation)
                @if($operation instanceof \BestSnipp\Eden\RenderProviders\RenderProvider)
                    @livewire($operation->component, $operation->params)
                @else
                    {!! $operation->render() !!}
                @endif
            @endforeach

            {!! $actionButtons->render('read') !!}
        </div>
    </div>

    <div class="mb-10 bg-white shadow sm:rounded-md divide-y divide-slate-100 text-slate-500 dark:text-slate-300 dark:bg-slate-700 dark:divide-slate-600">
        @foreach($fields as $field)
            {!! $field->render('read') !!}
        @endforeach
    </div>
</div>
