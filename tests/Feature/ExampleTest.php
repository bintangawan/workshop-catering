<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_and_role_restrictions(): void
    {
        $this->withoutVite();
        $this->seed();

        $this->get('/')->assertRedirect(route('login'));
        $this->get('/login')->assertOk();
        $this->post('/login', ['email' => 'pelanggan@catering.test', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => 'pelanggan@catering.test', 'password' => 'Workshop123!'])->assertRedirect(route('home'));
        $this->get('/')->assertRedirect(route('catalog'));
        $this->get('/admin')->assertForbidden();
        $this->post('/admin/menus')->assertForbidden();
        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_order_uses_auth_customer_and_menu_price_and_keeps_history(): void
    {
        $this->withoutVite();
        $this->seed();

        $customer = User::where('email', 'pelanggan@catering.test')->firstOrFail();
        $admin = User::where('email', 'admin@catering.test')->firstOrFail();
        $menu = Menu::where('price', 25000)->firstOrFail();

        $this->actingAs($customer)->post(route('order.store', $menu), ['quantity' => 0])->assertSessionHasErrors('quantity');
        $this->post(route('order.store', $menu), [
            'quantity' => 5,
            'unit_price' => 1,
            'user_id' => $admin->id,
            'status' => 'completed',
        ])->assertRedirect(route('orders'));

        $order = Order::sole();
        $this->assertSame($customer->id, $order->user_id);
        $this->assertSame('pending', $order->status);
        $this->assertSame(125000, $order->totalPrice());
        $this->patch(route('admin.orders.status', $order), ['status' => 'confirmed'])->assertForbidden();

        $anotherCustomer = User::create(['name' => 'Pelanggan Lain', 'email' => 'lain@catering.test', 'password' => 'testing-password']);
        $this->actingAs($anotherCustomer)->get('/orders')->assertOk()->assertDontSee('Rp125.000');

        $this->actingAs($admin)->get('/menus')->assertForbidden();
        $this->from('/admin/orders')->patch(route('admin.orders.status', $order), ['status' => 'completed'])
            ->assertRedirect('/admin/orders')->assertSessionHas('error');
        $this->assertSame('pending', $order->fresh()->status);
        $this->patch(route('admin.orders.status', $order), ['status' => 'confirmed'])->assertSessionHas('success');
        $this->patch(route('admin.orders.status', $order), ['status' => 'completed'])->assertSessionHas('success');
        $this->assertSame('completed', $order->fresh()->status);

        $menu->update(['price' => 30000]);
        $this->delete(route('admin.menus.destroy', $menu))->assertRedirect(route('admin.menus'));
        $this->assertFalse($menu->fresh()->is_active);
        $this->assertSame(125000, $order->fresh()->totalPrice());
        $this->get('/admin')->assertOk()->assertSee('Rp125.000');

        $this->actingAs($customer)->get(route('order', $menu))->assertNotFound();
        $this->post(route('order.store', $menu), ['quantity' => 1])->assertSessionHasErrors('quantity');
        $this->get('/orders')->assertOk()->assertSee('Rp125.000')->assertSee('Selesai');
    }
}
