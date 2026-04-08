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

// Catalog & Shopping Cart Logic
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::get('/cart', [ProductController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{id}', [ProductController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [ProductController::class, 'updateCart'])->name('cart.update');

/*
|--------------------------------------------------------------------------
| Customer Authentication (Users Table)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Auth View (Combined Login/Register)
    Route::get('/auth', function () { 
        return view('auth'); 
    })->name('login');

    // Authentication Processing
    Route::post('/login-process', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register-process', [AuthController::class, 'register'])->name('register');
});

// Logout accessible only for authenticated users
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Protected Routes (Middleware: Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Checkout & Payment
    Route::get('/checkout', [ProductController::class, 'viewCheckout'])->name('checkout.view');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    
    // Address Management
    Route::post('/address/store', [ProductController::class, 'storeAddress'])->name('address.store');
    Route::delete('/address/{id}', [ProductController::class, 'deleteAddress'])->name('address.delete');
    
    // Order History
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication & Dashboard (Admins Table)
|--------------------------------------------------------------------------
*/

// Admin Entry Points
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

// Admin Dashboard (Protected by Admin Guard)
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    
    // Main Dashboard (Inventory & Logistics Overview)
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // --- INVENTORY MANAGEMENT ---
    // Store New Product
    Route::post('/product/store', [AdminController::class, 'storeProduct'])->name('admin.product.store');
    // Delete Existing Product
    Route::delete('/product/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.product.delete');

    // --- LOGISTICS MANAGEMENT ---
    // Update Shipping/Delivery Status
    Route::patch('/order/{id}/update', [AdminController::class, 'updateShipping'])->name('admin.order.update');
});

/*
|--------------------------------------------------------------------------
| Payment Webhooks (Stripe Integration)
|--------------------------------------------------------------------------
*/

Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe']);