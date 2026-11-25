@extends('layouts.admin')
@section('title', 'Kelola Menu')
@section('content')
    <div class="page-heading">
        <div><h1>Kelola menu</h1><p>Atur menu, harga per porsi, dan ketersediaan di katalog.</p></div>
        <a href="#menu-form" class="button"><x-icon name="plus" /> {{ $editingMenu ? 'Ke form edit' : 'Tambah menu' }}</a>
    </div>

    <div class="menu-grid">
        @forelse ($menus as $menu)
            <article class="panel menu-card">
                @include('components.menu-photo', ['menu' => $menu])
                <div class="menu-body">
                    <div class="menu-title"><h2>{{ $menu->name }}</h2><span @class(['status', $menu->is_active ? 'status-completed' : 'status-neutral'])>{{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
                    <p>{{ $menu->description }}</p>
                    <div class="menu-price"><strong>Rp{{ number_format($menu->price, 0, ',', '.') }}</strong><small> / porsi</small></div>
                    <div class="menu-actions">
                        <a class="button button-small button-outline" href="{{ route('admin.menus', ['page' => $menus->currentPage(), 'edit' => $menu->id]).'#menu-form' }}">Edit</a>
                        @if (!$menu->orders_exists || $menu->is_active)
                            <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" data-confirm="{{ $menu->orders_exists ? 'Nonaktifkan menu ini?' : 'Hapus menu ini?' }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button-small button-quiet">{{ $menu->orders_exists ? 'Nonaktifkan' : 'Hapus' }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <p>Belum ada menu.</p>
        @endforelse
    </div>
    <div class="mt-5">{{ $menus->links() }}</div>

    <section class="panel order-form mt-5 max-w-xl" id="menu-form">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2>{{ $editingMenu ? 'Edit menu' : 'Tambah menu' }}</h2>
            @if ($editingMenu)<a class="text-link" href="{{ route('admin.menus') }}#menu-form">Batal edit</a>@endif
        </div>
        @include('admin.menus.form')
    </section>
@endsection
