@extends('layouts.app')

@section('title', 'Portal Demo')
@section('shell')
    <main class="portal">
        <section class="portal-photo" style="--portal-image: url('{{ asset('demo/ayam-matah.webp') }}')">
            <x-brand />
            <div>
                <p class="portal-eyebrow">WORKSHOP CATERING</p>
                <p class="portal-headline">Dari dapur<br>ke meja Anda.</p>
            </div>
        </section>
        <section class="portal-options">
            <div>
                <span class="demo-pill">Pratinjau workshop</span>
                <h1>Selamat datang</h1>
                <p>Pilih peran untuk mencoba alur pemesanan katering dengan data contoh.</p>
                <a class="role-card" href="{{ route('preview.dashboard') }}">
                    <span class="metric-icon"><x-icon name="grid" /></span>
                    <span><strong>Admin</strong><small>Kelola menu dan proses pesanan masuk.</small></span>
                    <x-icon name="arrow" class="ml-auto" />
                </a>
                <a class="role-card" href="{{ route('preview.catalog') }}">
                    <span class="metric-icon"><x-icon name="menu" /></span>
                    <span><strong>Pelanggan</strong><small>Pilih menu dan lihat riwayat pesanan.</small></span>
                    <x-icon name="arrow" class="ml-auto" />
                </a>
            </div>
        </section>
    </main>
@endsection
