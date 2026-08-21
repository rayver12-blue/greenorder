<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/auth/login',     [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/auth/login',    [AuthController::class, 'login'])->name('auth.login.post');
    Route::get('/auth/register',  [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register.post');
    Route::get('/auth/forgot',    [AuthController::class, 'showForgot'])->name('auth.forgot');
    Route::post('/auth/forgot',   [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/auth/reset/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/auth/reset',    [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
Route::middleware('auth')->put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

// Customer
Route::middleware(['auth', 'role:user'])->prefix('customer')->group(function () {
    Route::get('/home',           [CustomerController::class, 'home'])->name('customer.home');
    Route::get('/products',        [CustomerController::class, 'productsJson'])->name('customer.products.json');
    Route::post('/checkout',      [CustomerController::class, 'checkout'])->name('customer.checkout');
    Route::get('/payment',        [CustomerController::class, 'showPayment'])->name('customer.payment');
    Route::post('/pay',           [CustomerController::class, 'pay'])->name('customer.pay');
    Route::post('/order',         [CustomerController::class, 'placeOrder'])->name('customer.order');
    Route::get('/orders',         [CustomerController::class, 'myOrders'])->name('customer.orders');
    Route::get('/orders/{order}', [CustomerController::class, 'orderDetail'])->name('customer.order.detail');
    Route::patch('/orders/{order}/cancel', [CustomerController::class, 'cancelOrder'])->name('customer.orders.cancel');
    Route::get('/orders/{order}/receipt',  [CustomerController::class, 'orderReceipt'])->name('customer.orders.receipt');
    Route::post('/reviews',                [CustomerController::class, 'storeReview'])->name('customer.reviews.store');
    Route::post('/wishlist/{product}',     [CustomerController::class, 'toggleWishlist'])->name('customer.wishlist.toggle');
    Route::get('/order-statuses',          [CustomerController::class, 'activeOrderStatuses'])->name('customer.order.statuses');
    Route::get('/profile',        [ProfileController::class, 'customerProfile'])->name('customer.profile');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',             [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products',              [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products',             [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::post('/products/bulk-toggle', [AdminController::class, 'bulkToggle'])->name('admin.products.bulk-toggle');
    Route::put('/products/{product}',    [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::get('/customers',             [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/orders',                [AdminController::class, 'orders'])->name('admin.orders');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::get('/reports',               [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/profile',               [ProfileController::class, 'adminProfile'])->name('admin.profile');
});
