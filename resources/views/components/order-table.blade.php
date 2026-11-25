@props(['orders', 'admin' => false, 'actions' => false])
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
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><strong>#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</strong><small>{{ $order->created_at?->copy()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY') ?? '—' }}</small></td>
                    <td class="font-semibold">{{ $order->menu->name }}</td>
                    @if ($admin)<td>{{ $order->customer->name }}</td>@endif
                    <td>{{ $order->quantity }}</td>
                    @unless ($admin)<td>Rp{{ number_format($order->unit_price, 0, ',', '.') }}</td>@endunless
                    <td class="font-semibold">Rp{{ number_format($order->totalPrice(), 0, ',', '.') }}</td>
                    <td><x-status :status="$order->status" /></td>
                    @if ($actions)
                        <td>
                            @if ($order->status !== 'completed')
                                <form method="POST" action="{{ route('admin.orders.status', $order) }}" data-confirm="Perbarui status pesanan ini?">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->status === 'pending' ? 'confirmed' : 'completed' }}">
                                    <button type="submit" class="button button-small button-outline">{{ $order->status === 'pending' ? 'Konfirmasi' : 'Tandai selesai' }}</button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="{{ $actions ? 7 : 6 }}" class="text-muted">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
