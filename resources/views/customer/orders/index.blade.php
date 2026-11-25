@extends('layouts.customer')
@section('title', 'Pesanan Saya')
@section('content')
    <div class="page-heading">
        <div><h1>Pesanan saya</h1><p>Lihat riwayat dan status pesanan Anda.</p></div>
        <a class="button" href="{{ route('preview.catalog') }}">Lihat katalog <x-icon name="arrow" /></a>
    </div>
    <section class="panel table-panel">
        <div class="section-heading"><div><h2>Riwayat pesanan</h2><p>{{ $users[$customerId]['name'] }} · data demo</p></div></div>
        <x-order-table :orders="collect($orders)->where('user_id', $customerId)" :$menus :$users />
    </section>
@endsection
