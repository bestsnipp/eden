@foreach(\BestSnipp\Eden\Facades\Eden::headerActions() as $headerAction)
    @if($headerAction->isAuthorizedToSee())
        @livewire($headerAction->component, $headerAction->params)
    @endif
@endforeach
