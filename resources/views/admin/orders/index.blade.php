@extends('layouts.admin')
@section('title', 'Pesanan Masuk')
@section('content')
    <div class="page-heading"><div><h1>Pesanan masuk</h1><p>Konfirmasi pesanan pelanggan dan tandai saat selesai.</p></div></div>
    <section class="panel table-panel">
        <div class="section-heading"><div><h2>Daftar pesanan</h2><p>{{ $orders->total() }} pesanan</p></div></div>
        <x-order-table :$orders admin actions />
    </section>
    <div class="mt-5">{{ $orders->links() }}</div>
@endsection
