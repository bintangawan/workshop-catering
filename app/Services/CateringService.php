<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CateringService
{
    public function deleteMenu(Menu $menu): bool
    {
        return DB::transaction(function () use ($menu) {
            $lockedMenu = Menu::query()->lockForUpdate()->findOrFail($menu->id);

            if ($lockedMenu->orders()->exists()) {
                $lockedMenu->update(['is_active' => false]);

                return false;
            }

            $lockedMenu->delete();

            return true;
        });
    }

    public function placeOrder(User $customer, Menu $menu, int $quantity): Order
    {
        return DB::transaction(function () use ($customer, $menu, $quantity) {
            $lockedMenu = Menu::query()->lockForUpdate()->findOrFail($menu->id);

            if (! $lockedMenu->is_active) {
                throw ValidationException::withMessages(['quantity' => 'Menu ini sudah tidak tersedia.']);
            }

            $order = new Order;
            $order->customer()->associate($customer);
            $order->menu()->associate($lockedMenu);
            $order->quantity = $quantity;
            $order->unit_price = $lockedMenu->price;
            $order->save();

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, string $status): bool
    {
        $previous = match ($status) {
            Order::STATUS_CONFIRMED => Order::STATUS_PENDING,
            Order::STATUS_COMPLETED => Order::STATUS_CONFIRMED,
            default => null,
        };

        return $previous !== null && Order::query()
            ->whereKey($order->id)
            ->where('status', $previous)
            ->update(['status' => $status]) === 1;
    }
}
