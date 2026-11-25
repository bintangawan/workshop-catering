<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CateringController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'welcome')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'home'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->middleware('can:access-admin')->group(function () {
        Route::get('/', [CateringController::class, 'dashboard'])->name('dashboard');
        Route::get('/menus', [CateringController::class, 'menus'])->name('menus');
        Route::post('/menus', [CateringController::class, 'storeMenu'])->name('menus.store');
        Route::patch('/menus/{menu}', [CateringController::class, 'updateMenu'])->name('menus.update');
        Route::delete('/menus/{menu}', [CateringController::class, 'deleteMenu'])->name('menus.destroy');
        Route::get('/orders', [CateringController::class, 'incomingOrders'])->name('orders');
        Route::patch('/orders/{order}/status', [CateringController::class, 'updateOrderStatus'])->name('orders.status');
    });

    Route::middleware('can:access-customer')->group(function () {
        Route::get('/menus', [CateringController::class, 'catalog'])->name('catalog');
        Route::get('/menus/{menu}/order', [CateringController::class, 'orderForm'])->name('order');
        Route::post('/menus/{menu}/order', [CateringController::class, 'storeOrder'])->name('order.store');
        Route::get('/orders', [CateringController::class, 'customerOrders'])->name('orders');
    });
});
