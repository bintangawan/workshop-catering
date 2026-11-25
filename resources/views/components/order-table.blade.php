@props(['orders', 'menus', 'users', 'admin' => false, 'actions' => false, 'customerId' => null])
<div class="table-scroll">
    <table class="data-table">
        <thead><tr>
            <th>Pesanan</th><th>Menu</th>
            @if ($admin)<th>Pelanggan</th>@endif
            <th>Porsi</th>
            @unless ($admin)<th>Harga per porsi</th>@endunless
            <th>Total</th><th>Status</th>
            @if ($actions)<th>Aksi</th>@endif
        </tr></thead>
        <tbody @if($actions) data-demo-order-list="admin" data-demo-customer="{{ $users[$customerId]['name'] }}" @elseif(!$admin) data-demo-order-list="customer" @endif>
            @forelse ($orders as $order)
                <tr>
                    <td><strong>#{{ str_pad($order['id'], 3, '0', STR_PAD_LEFT) }}</strong><small>{{ \Carbon\Carbon::parse($order['created_at'])->locale('id')->isoFormat('D MMM YYYY') }}</small></td>
                    <td class="font-semibold">{{ $menus[$order['menu_id']]['name'] }}</td>
                    @if ($admin)<td>{{ $users[$order['user_id']]['name'] }}</td>@endif
                    <td>{{ $order['quantity'] }}</td>
                    @unless ($admin)<td>Rp{{ number_format($order['unit_price'], 0, ',', '.') }}</td>@endunless
                    <td class="font-semibold">Rp{{ number_format($order['quantity'] * $order['unit_price'], 0, ',', '.') }}</td>
                    <td><x-status :status="$order['status']" :data-order-status="$actions || !$admin ? $order['id'] : null" /></td>
                    @if ($actions)
                        <td>
                            @if ($order['status'] !== 'completed')
                                <button type="button" class="button button-small button-outline" data-order-action="{{ $order['id'] }}" data-current-status="{{ $order['status'] }}">{{ $order['status'] === 'pending' ? 'Konfirmasi' : 'Tandai selesai' }}</button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="{{ $actions ? 7 : 6 }}" data-empty-orders class="text-muted">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
