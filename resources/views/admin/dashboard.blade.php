@extends('layouts.admin')

@section('title', 'Dashboard')
@section('content')
    @php
        $scale = max(4, $daily->max('count'));
        $metrics = [
            ['orders', 'Total pesanan', $orderCount, 'Selama 7 hari terakhir'],
            ['wallet', 'Nilai pesanan', 'Rp'.number_format($value, 0, ',', '.'), 'Jumlah seluruh nilai pesanan'],
            ['clock', 'Perlu konfirmasi', $statusCounts['pending'], 'Pesanan berstatus Menunggu'],
            ['menu', 'Menu aktif', $menuCount, 'Tampil di katalog pelanggan'],
        ];
    @endphp
    <div class="page-heading">
        <div><h1>Dashboard katering</h1><p>Pantau pesanan dan ketersediaan menu.</p></div>
        <span class="date-label"><x-icon name="clock" /> {{ $periodLabel }}</span>
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
                @foreach ($daily as $day)
                    @php($count = $day['count'])
                    <div class="chart-column">
                        <div class="bar-track"><div class="bar-fill" style="--bar-height: {{ $count / $scale * 100 }}%" title="{{ $count }} pesanan"><span>{{ $count }}</span></div></div>
                        <small>{{ $day['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="panel chart">
            <h2>Status pesanan</h2><p>Jumlah pesanan per status</p>
            <div class="status-chart">
                @foreach ($statusCounts as $status => $count)
                    <div class="status-chart-row">
                        <div><x-status :status="$status" /><strong>{{ $count }}</strong></div>
                        <div class="status-track"><div class="status-fill status-fill-{{ $status }}" style="--bar-width: {{ $count / max(1, $orderCount) * 100 }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
    <section class="panel table-panel">
        <div class="section-heading"><div><h2>Pesanan terbaru</h2><p>Empat pesanan paling baru.</p></div><a class="text-link" href="{{ route('admin.orders') }}">Lihat semua <x-icon name="arrow" /></a></div>
        <x-order-table :$orders admin />
    </section>
@endsection
