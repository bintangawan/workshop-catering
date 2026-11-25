@extends('layouts.app')

@section('shell')
    <header class="customer-bar">
        <div class="customer-nav">
            <x-brand />
            <nav aria-label="Menu pelanggan">
                <a href="{{ route('catalog') }}" @class(['is-current' => request()->routeIs('catalog', 'order')])>Katalog Menu</a>
                <a href="{{ route('orders') }}" @class(['is-current' => request()->routeIs('orders')])>Pesanan Saya</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3">
                @csrf
                <span class="hidden text-sm font-bold sm:block">{{ auth()->user()->name }}</span>
                <button type="submit" class="button button-small button-outline">Keluar</button>
            </form>
        </div>
    </header>
    <main class="customer-wrap">@yield('content')</main>
@endsection
