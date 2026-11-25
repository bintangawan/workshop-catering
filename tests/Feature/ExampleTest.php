<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_guest_registration_always_creates_a_customer(): void
    {
        $this->withoutVite();
        $data = [
            'name' => 'Pelanggan Baru',
            'email' => 'Baru@catering.test',
            'password' => 'Workshop123!',
            'password_confirmation' => 'Workshop123!',
            'role' => 'admin',
        ];

        $this->get('/login')->assertSee('Daftar sebagai pelanggan');
        $this->get('/register')->assertOk();
        $this->post('/register', array_replace($data, ['password_confirmation' => 'salah']))->assertSessionHasErrors('password');
        $this->post('/register', $data)->assertRedirect(route('catalog'));
        $user = User::whereEmail('baru@catering.test')->sole();
        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertNotSame($data['password'], $user->password);
        $this->get('/admin')->assertForbidden();
        $this->post('/logout');
        $this->post('/register', $data)->assertSessionHasErrors('email');
        $this->post('/login', ['email' => $data['email'], 'password' => $data['password']])->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_menu_photos_are_validated_replaced_and_deleted_with_the_menu(): void
    {
        Storage::fake('public');
        $this->seed();
        $admin = User::whereEmail('admin@catering.test')->sole();
        $customer = User::whereEmail('pelanggan@catering.test')->sole();
        $data = ['name' => 'Menu Baru', 'price' => 25000, 'is_active' => 1];

        $this->actingAs($admin);
        $this->post('/admin/menus', $data + ['image' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf')])
            ->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('menus', ['name' => 'Menu Baru']);
        $this->post('/admin/menus', $data + [
            'image' => new UploadedFile(public_path('demo/ayam-matah.webp'), 'foto.webp', null, null, true),
        ])->assertRedirect(route('admin.menus'));

        $menu = Menu::whereName('Menu Baru')->sole();
        $firstPath = $menu->image_path;
        Storage::disk('public')->assertExists($firstPath);
        $this->patch(route('admin.menus.update', $menu), $data)->assertRedirect(route('admin.menus'));
        $this->assertSame($firstPath, $menu->fresh()->image_path);

        $this->patch(route('admin.menus.update', $menu), $data + [
            'image' => new UploadedFile(public_path('demo/ayam-bakar.webp'), 'baru.webp', null, null, true),
        ])->assertRedirect(route('admin.menus'));

        $secondPath = $menu->fresh()->image_path;
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
        $this->withoutVite()->actingAs($customer)->get('/menus')->assertOk()->assertSee('/storage/'.$secondPath);
        $this->actingAs($admin)->delete(route('admin.menus.destroy', $menu))->assertRedirect(route('admin.menus'));
        Storage::disk('public')->assertMissing($secondPath);
        $this->assertModelMissing($menu);
    }
}
