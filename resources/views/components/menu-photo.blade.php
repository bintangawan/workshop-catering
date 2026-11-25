@if (isset($photos[$menu['id']]))
    <img class="menu-photo" src="{{ asset($photos[$menu['id']]) }}" alt="{{ $menu['name'] }}" width="640" height="380">
@else
    <div class="menu-photo menu-placeholder"><x-icon name="menu" class="size-9" /></div>
@endif
