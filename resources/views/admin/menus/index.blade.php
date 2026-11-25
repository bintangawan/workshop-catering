@extends('layouts.admin')
@section('title', 'Kelola Menu')
@section('content')
    <div class="page-heading">
        <div><h1>Kelola menu</h1><p>Tambah dan edit menu. Menu tanpa riwayat pesanan bisa dihapus.</p></div>
        <button type="button" class="button" data-open-menu-dialog><x-icon name="plus" /> Tambah menu</button>
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
                        <a class="button button-small button-outline" href="{{ route('admin.menus', ['page' => $menus->currentPage(), 'edit' => $menu->id]) }}">Edit</a>
                        @if ($menu->orders_exists)
                            <button type="button" class="button button-small button-quiet disabled:cursor-not-allowed disabled:opacity-60" disabled aria-describedby="delete-note-{{ $menu->id }}">Hapus</button>
                        @endif
                        @if (!$menu->orders_exists || $menu->is_active)
                            <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" data-confirm="{{ $menu->orders_exists ? 'Nonaktifkan menu ini?' : 'Hapus menu ini?' }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" @class(['button button-small', 'button-quiet' => $menu->orders_exists, 'bg-red-50 text-red-700 hover:bg-red-100' => !$menu->orders_exists])>{{ $menu->orders_exists ? 'Nonaktifkan' : 'Hapus' }}</button>
                            </form>
                        @endif
                    </div>
                    @if ($menu->orders_exists)
                        <small id="delete-note-{{ $menu->id }}" class="text-muted">Menu ini sudah dipesan. Riwayat pesanan tetap tersimpan.</small>
                    @endif
                </div>
            </article>
        @empty
            <p>Belum ada menu.</p>
        @endforelse
    </div>
    <div class="mt-5">{{ $menus->links() }}</div>

    <dialog class="menu-dialog" data-menu-dialog @if($editingMenu || $errors->any()) data-open-on-load @endif aria-labelledby="menu-dialog-title">
        <div class="grid gap-4 p-5 sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <h2 id="menu-dialog-title">{{ $editingMenu ? 'Edit menu' : 'Tambah menu' }}</h2>
                <button type="button" data-close-menu-dialog class="button button-small button-quiet">Tutup</button>
            </div>
            @include('admin.menus.form')
        </div>
    </dialog>
@endsection
