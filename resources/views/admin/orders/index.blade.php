@extends('layouts.admin')
@section('title', 'Pesanan Masuk')
@section('content')
    <div class="page-heading"><div><h1>Pesanan masuk</h1><p>Konfirmasi pesanan pelanggan dan tandai saat selesai.</p></div><span class="demo-pill">Data demo</span></div>
    <section class="panel table-panel">
        <div class="section-heading"><div><h2>Daftar pesanan</h2><p>{{ count($orders) }} pesanan contoh</p></div></div>
        <x-order-table :$orders :$menus :$users :$customerId admin actions />
    </section>
@endsection
