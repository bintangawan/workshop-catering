<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('preview.home');

// GET routes for the Blade prototype. Fixtures are read from disk only.
Route::prefix('preview')->name('preview.')->group(function () {
    $demo = require resource_path('demo/catering.php');

    Route::view('/admin', 'admin.dashboard', $demo)->name('dashboard');
    Route::view('/admin/menus', 'admin.menus.index', $demo)->name('admin.menus');
    Route::view('/admin/orders', 'admin.orders.index', $demo)->name('admin.orders');
    Route::view('/menus', 'customer.catalog.index', $demo)->name('catalog');
    Route::view('/orders', 'customer.orders.index', $demo)->name('orders');

    Route::get('/menus/{menu}/order', function (int $menu) use ($demo) {
        $selectedMenu = $demo['menus'][$menu] ?? null;
        abort_unless($selectedMenu && $selectedMenu['is_active'], 404);

        return view('customer.orders.create', $demo + ['menu' => $selectedMenu]);
    })->whereNumber('menu')->name('order');
});
