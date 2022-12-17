@extends("eden::layouts.app")

@section('header', $title)

@section('content')
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-3 my-4">
        @foreach($components as $component)
            @if($component->isAuthorizedToSee())
                @livewire($component->component, $component->params)
            @endif
        @endforeach
    </div>
@endsection
