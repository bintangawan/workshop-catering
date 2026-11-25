<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class CateringService
{
    public function saveMenu(Menu $menu, array $data): void
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        $newPath = $image?->store('menus', 'public');

        if ($newPath === false) {
            throw ValidationException::withMessages(['image' => 'Foto gagal disimpan. Coba unggah lagi.']);
        }

        try {
            $oldPath = DB::transaction(function () use ($menu, $data, $newPath) {
                $record = $menu->exists ? Menu::query()->lockForUpdate()->findOrFail($menu->id) : $menu;
                $record->fill($data);
                $oldPath = $newPath !== null ? $record->image_path : null;

                if ($newPath !== null) {
                    $record->image_path = $newPath;
                }

                $record->save();

                return $oldPath;
            });
        } catch (Throwable $exception) {
            if ($newPath !== null) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($oldPath !== null) {
            Storage::disk('public')->delete($oldPath);
        }
    }

    public function deleteMenu(Menu $menu): bool
    {
        [$deleted, $imagePath] = DB::transaction(function () use ($menu) {
            $lockedMenu = Menu::query()->lockForUpdate()->findOrFail($menu->id);

            if ($lockedMenu->orders()->exists()) {
                $lockedMenu->update(['is_active' => false]);

                return [false, null];
            }

            $lockedMenu->delete();

            return [true, $lockedMenu->image_path];
        });

        if ($imagePath !== null) {
            Storage::disk('public')->delete($imagePath);
        }

        return $deleted;
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
