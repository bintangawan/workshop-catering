@extends('layouts.admin')

@section('title', 'Dashboard')
@section('content')
    @php
        $orders = collect($orders);
        $menuCount = collect($menus)->where('is_active', true)->count();
        $statusCounts = collect(['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai'])
            ->map(fn ($label, $status) => ['label' => $label, 'count' => $orders->where('status', $status)->count()]);
        $value = $orders->sum(fn ($order) => $order['quantity'] * $order['unit_price']);
        $daily = collect(range(20, 26))->map(fn ($day) => $orders->where('created_at', "2026-11-$day")->count());
        $scale = max(4, $daily->max());
        $metrics = [
            ['orders', 'Total pesanan', $orders->count(), 'Dalam periode contoh'],
            ['wallet', 'Nilai pesanan', 'Rp'.number_format($value, 0, ',', '.'), 'Jumlah seluruh nilai pesanan'],
            ['clock', 'Perlu konfirmasi', $statusCounts['pending']['count'], 'Pesanan berstatus Menunggu'],
            ['menu', 'Menu aktif', $menuCount, 'Tampil di katalog pelanggan'],
        ];
    @endphp
    <div class="page-heading">
        <div><h1>Dashboard katering</h1><p>Pantau pesanan dan ketersediaan menu.</p></div>
        <span class="date-label"><x-icon name="clock" /> 20–26 November 2026</span>
    </div>
    <div class="metrics">
        @foreach ($metrics as [$icon, $label, $metric, $note])
            <section class="panel metric">
                <div class="metric-top"><span>{{ $label }}</span><span class="metric-icon"><x-icon :name="$icon" /></span></div>
                <strong>{{ $metric }}</strong>
                <small>{{ $note }}</small>
            </section>
        @endforeach
    </div>
    <div class="charts">
        <section class="panel chart">
            <h2>Tren pesanan</h2><p>Jumlah pesanan per hari</p>
            <div class="chart-columns">
                @foreach ($daily as $index => $count)
                    <div class="chart-column">
                        <div class="bar-track"><div class="bar-fill" style="--bar-height: {{ $count / $scale * 100 }}%" title="{{ $count }} pesanan"><span>{{ $count }}</span></div></div>
                        <small>{{ 20 + $index }} Nov</small>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="panel chart">
            <h2>Status pesanan</h2><p>Jumlah pesanan per status</p>
            <div class="status-chart">
                @foreach ($statusCounts as $status => $item)
                    <div class="status-chart-row">
                        <div><x-status :status="$status" /><strong>{{ $item['count'] }}</strong></div>
                        <div class="status-track"><div class="status-fill status-fill-{{ $status }}" style="--bar-width: {{ $item['count'] / max(1, $orders->count()) * 100 }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
    <section class="panel table-panel">
        <div class="section-heading"><div><h2>Pesanan terbaru</h2><p>Ringkasan pesanan masuk dari data contoh.</p></div><a class="text-link" href="{{ route('preview.admin.orders') }}">Lihat semua <x-icon name="arrow" /></a></div>
        <x-order-table :orders="$orders->take(4)" :$menus :$users admin />
    </section>
@endsection
