@extends('layouts.admin')
@section('title', 'Kelola Menu')
@section('content')
    <div class="page-heading">
        <div><h1>Kelola menu</h1><p>Atur menu, harga per porsi, dan ketersediaan di katalog.</p></div>
        <button type="button" class="button" data-menu-form-open><x-icon name="plus" /> Tambah menu</button>
    </div>
    <div class="menu-grid" data-menu-grid>
        @foreach ($menus as $menu)
            @php($hasOrders = collect($orders)->contains('menu_id', $menu['id']))
            <article class="panel menu-card" data-menu-card>
                @include('components.menu-photo', ['menu' => $menu])
                <div class="menu-body">
                    <div class="menu-title"><h2 data-menu-name>{{ $menu['name'] }}</h2><span @class(['status', $menu['is_active'] ? 'status-completed' : 'status-neutral']) data-menu-status>{{ $menu['is_active'] ? 'Aktif' : 'Nonaktif' }}</span></div>
                    <p data-menu-description>{{ $menu['description'] }}</p>
                    <div class="menu-price"><strong data-menu-price>Rp{{ number_format($menu['price'], 0, ',', '.') }}</strong><small> / porsi</small></div>
                    <div class="menu-actions">
                        <button type="button" class="button button-small button-outline" data-menu-form-open data-menu="{{ json_encode($menu, JSON_THROW_ON_ERROR) }}">Edit</button>
                        @if ($hasOrders)
                            <button type="button" class="button button-small button-quiet" data-menu-toggle>{{ $menu['is_active'] ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                        @else
                            <button type="button" class="button button-small button-quiet" data-menu-delete>Hapus</button>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
    <template data-new-menu>
        <article class="panel menu-card" data-menu-card>
            <div class="menu-photo menu-placeholder"><x-icon name="menu" class="size-9" /></div>
            <div class="menu-body">
                <div class="menu-title"><h2 data-menu-name></h2><span class="status" data-menu-status></span></div>
                <p data-menu-description></p>
                <div class="menu-price"><strong data-menu-price></strong><small> / porsi</small></div>
                <div class="menu-actions">
                    <button type="button" class="button button-small button-outline" data-menu-form-open>Edit</button>
                    <button type="button" class="button button-small button-quiet" data-menu-delete>Hapus</button>
                </div>
            </div>
        </article>
    </template>
    <dialog class="menu-dialog" data-menu-dialog>
        <form data-menu-form>
            <div class="dialog-heading"><h2 data-dialog-title>Tambah menu</h2><button class="button button-small button-quiet" type="button" data-dialog-close>Tutup</button></div>
            @include('admin.menus.form')
            <button type="submit" class="button mt-3">Simpan simulasi</button>
        </form>
    </dialog>
@endsection
