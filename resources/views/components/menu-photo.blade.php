@php
    $photo = match ($menu->name) {
        'Nasi Ayam Sambal Matah' => 'demo/ayam-matah.webp',
        'Nasi Ayam Bakar' => 'demo/ayam-bakar.webp',
        'Nasi Rendang' => 'demo/rendang.webp',
        default => null,
    };
@endphp
@if ($photo)
    <img class="menu-photo" src="{{ asset($photo) }}" alt="{{ $menu->name }}" width="640" height="380">
@else
    <div class="menu-photo menu-placeholder"><x-icon name="menu" class="size-9" /></div>
@endif
