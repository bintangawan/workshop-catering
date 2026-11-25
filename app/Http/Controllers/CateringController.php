<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Services\CateringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CateringController extends Controller
{
    public function __construct(private CateringService $catering) {}

    public function dashboard(): View
    {
        $today = today('Asia/Jakarta');
        $days = collect(range(6, 0))->map(fn ($offset) => $today->copy()->subDays($offset));
        $start = $days->first()->copy()->utc();
        $end = $today->copy()->addDay()->utc();
        $period = Order::query()->where('created_at', '>=', $start)->where('created_at', '<', $end);

        $summary = (clone $period)->selectRaw('status, COUNT(*) AS orders_count, SUM(quantity * unit_price) AS orders_value')
            ->groupBy('status')->get()->keyBy('status');
        $statusCounts = collect([Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED])
            ->mapWithKeys(fn ($status) => [$status => (int) ($summary->get($status)?->orders_count ?? 0)]);
        $ordersByDay = (clone $period)->get(['created_at'])->countBy(
            fn (Order $order) => $order->created_at->copy()->setTimezone('Asia/Jakarta')->toDateString(),
        );
        $daily = $days->map(fn ($day) => [
            'label' => $day->locale('id')->isoFormat('D MMM'),
            'count' => $ordersByDay->get($day->toDateString(), 0),
        ]);

        return view('admin.dashboard', [
            'orders' => Order::query()->with(['customer', 'menu'])->latest()->orderByDesc('id')->limit(4)->get(),
            'daily' => $daily,
            'statusCounts' => $statusCounts,
            'orderCount' => $statusCounts->sum(),
            'value' => (int) $summary->sum('orders_value'),
            'menuCount' => Menu::query()->where('is_active', true)->count(),
            'periodLabel' => $days->first()->locale('id')->isoFormat('D MMM').' – '.$today->locale('id')->isoFormat('D MMM YYYY'),
        ]);
    }

    public function menus(Request $request): View
    {
        $editId = $request->integer('edit');

        return view('admin.menus.index', [
            'editingMenu' => $editId > 0 ? Menu::findOrFail($editId) : null,
            'menus' => Menu::query()->withExists('orders')->latest()->orderByDesc('id')->paginate(12),
        ]);
    }

    public function storeMenu(Request $request): RedirectResponse
    {
        $this->catering->saveMenu(new Menu, $this->menuData($request));

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, Menu $menu): RedirectResponse
    {
        $this->catering->saveMenu($menu, $this->menuData($request));

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil diperbarui.');
    }

    public function deleteMenu(Menu $menu): RedirectResponse
    {
        $deleted = $this->catering->deleteMenu($menu);

        return redirect()->route('admin.menus')->with('success', $deleted
            ? 'Menu berhasil dihapus.'
            : 'Menu dinonaktifkan agar riwayat pesanan tetap tersimpan.');
    }

    public function catalog(): View
    {
        return view('customer.catalog.index', [
            'menus' => Menu::query()->where('is_active', true)->latest()->orderByDesc('id')->paginate(12),
        ]);
    }

    public function orderForm(Menu $menu): View
    {
        abort_unless($menu->is_active, 404);

        return view('customer.orders.create', compact('menu'));
    }

    public function storeOrder(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'between:1,65535']]);
        $this->catering->placeOrder($request->user(), $menu, (int) $data['quantity']);

        return redirect()->route('orders')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function customerOrders(Request $request): View
    {
        return view('customer.orders.index', [
            'orders' => $request->user()->orders()->with('menu')->latest()->orderByDesc('id')->paginate(12),
        ]);
    }

    public function incomingOrders(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()->with(['customer', 'menu'])->latest()->orderByDesc('id')->paginate(12),
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in([Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED])]]);
        $updated = $this->catering->updateOrderStatus($order, $data['status']);

        return back()->with(
            $updated ? 'success' : 'error',
            $updated ? 'Status pesanan berhasil diperbarui.' : 'Status tidak bisa diubah dengan urutan tersebut.',
        );
    }

    private function menuData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'between:1,4294967295'],
            'is_active' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
