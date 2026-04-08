<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest Access)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Catalog & Cart Logic
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::get('/cart', [ProductController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{id}', [ProductController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [ProductController::class, 'updateCart'])->name('cart.update');

/*
|--------------------------------------------------------------------------
| Customer Authentication (Tabel: Users)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Tampilan Form (auth.blade.php)
    Route::get('/auth', function () { 
        return view('auth'); 
    })->name('login');

    // Proses Post
    Route::post('/login-process', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register-process', [AuthController::class, 'register'])->name('register');
});

// Logout harus bisa diakses saat sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Protected Routes (Middleware: Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [ProductController::class, 'viewCheckout'])->name('checkout.view');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    
    Route::post('/address/store', [ProductController::class, 'storeAddress'])->name('address.store');
    Route::delete('/address/{id}', [ProductController::class, 'deleteAddress'])->name('address.delete');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication & Dashboard (Tabel: Admins)
|--------------------------------------------------------------------------
*/

// Pintu Masuk Admin
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

// Dashboard Admin (Proteksi dengan Guard Admin)
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    
    // Dashboard Utama (Inventory & Logistics)
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // --- INVENTORY MANAGEMENT ---
    // Simpan Produk Baru
    Route::post('/product/store', [AdminController::class, 'storeProduct'])->name('admin.product.store');
    // Hapus Produk (Fitur Baru)
    Route::delete('/product/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.product.delete');

    // --- LOGISTICS MANAGEMENT ---
    // Update Status Pengiriman
    Route::patch('/order/{id}/update', [AdminController::class, 'updateShipping'])->name('admin.order.update');
});

/*
|--------------------------------------------------------------------------
| Payment Webhooks (Stripe)
|--------------------------------------------------------------------------
*/

Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe']);