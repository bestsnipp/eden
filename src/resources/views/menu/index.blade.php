{{-- MENU ACTIVE CLASS -> bg-primary-500 text-white shadow-md shadow-primary-300 --}}
<ul x-data="{selected: '{{ url()->current() }}'}" data-dusk="mainMenu" class="px-1 pt-4 pb-24">
    @foreach($menu as $menuItem)
        {!! $menuItem->render() !!}
    @endforeach
</ul>
