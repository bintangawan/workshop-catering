@extends('layouts.customer')
@section('title', 'Katalog Menu')
@section('content')
    <section class="catalog-hero">
        <div><h1>Pilih menu katering Anda.</h1><p>Temukan hidangan yang tersedia dan pesan sesuai jumlah porsi yang Anda perlukan.</p><a href="#menu-tersedia" class="button hero-button">Lihat menu <x-icon name="arrow" /></a></div>
        <img src="{{ asset('demo/ayam-bakar.webp') }}" alt="Hidangan nasi ayam bakar" width="640" height="380">
    </section>
    <div id="menu-tersedia" class="page-heading section-gap"><div><h2>Menu tersedia</h2><p>Harga ditampilkan per porsi.</p></div></div>
    <div class="menu-grid customer-grid">
        @forelse (collect($menus)->where('is_active', true) as $menu)
            <article class="panel menu-card">
                @include('components.menu-photo', ['menu' => $menu])
                <div class="menu-body">
                    <h3>{{ $menu['name'] }}</h3><p>{{ $menu['description'] }}</p>
                    <div class="menu-price"><strong>Rp{{ number_format($menu['price'], 0, ',', '.') }}</strong><small> / porsi</small></div>
                    <a href="{{ route('preview.order', $menu['id']) }}" class="button w-full">Pesan menu <x-icon name="arrow" /></a>
                </div>
            </article>
        @empty
            <p>Belum ada menu aktif.</p>
        @endforelse
    </div>
@endsection
