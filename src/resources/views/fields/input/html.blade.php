@if($usingFullSpace)
    {!! $title !!}
    @include('eden::fields.help')
@else
<div class="px-5 py-4 my-3">
    {!! $title !!}
    @include('eden::fields.help')
</div>
@endif
