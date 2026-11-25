@extends('layouts.app')

@section('shell')
    <header class="customer-bar">
        <div class="customer-nav">
            <x-brand />
            <nav aria-label="Menu pelanggan">
                <a href="{{ route('preview.catalog') }}" @class(['is-current' => request()->routeIs('preview.catalog', 'preview.order')])>Katalog Menu</a>
                <a href="{{ route('preview.orders') }}" @class(['is-current' => request()->routeIs('preview.orders')])>Pesanan Saya</a>
            </nav>
            <a class="profile" href="{{ route('preview.home') }}" aria-label="Sarah Paramitha, ganti peran demo"><span class="avatar">SP</span><span class="hidden sm:block"><strong>Sarah Paramitha</strong><small>Pelanggan demo</small></span></a>
        </div>
    </header>
    <main class="customer-wrap">@yield('content')</main>
@endsection
