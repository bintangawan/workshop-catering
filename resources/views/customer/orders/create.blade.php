@extends('layouts.customer')
@section('title', 'Form Pemesanan')
@section('content')
    <a class="text-link" href="{{ route('catalog') }}"><x-icon name="back" /> Kembali ke katalog</a>
    <div class="page-heading mt-5"><div><h1>Pesan menu</h1><p>Masukkan jumlah porsi untuk melihat estimasi total.</p></div></div>
    <div class="order-grid">
        <article class="panel menu-card">
            @include('components.menu-photo')
            <div class="menu-body"><h2>{{ $menu->name }}</h2><p>{{ $menu->description }}</p><div class="menu-price"><strong>Rp{{ number_format($menu->price, 0, ',', '.') }}</strong><small> / porsi</small></div></div>
        </article>
        <form method="POST" action="{{ route('order.store', $menu) }}" class="panel order-form" data-order-form data-unit-price="{{ $menu->price }}">
            @csrf
            <h2>Ringkasan pesanan</h2>
            <label class="field"><span>Jumlah porsi</span><input type="number" name="quantity" min="1" max="65535" step="1" value="{{ old('quantity', 1) }}" required></label>
            @error('quantity') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
            <div class="order-total"><span>Estimasi total</span><output aria-live="polite">Rp{{ number_format($menu->price, 0, ',', '.') }}</output></div>
            <button type="submit" class="button w-full">Buat pesanan <x-icon name="arrow" /></button>
        </form>
    </div>
@endsection
