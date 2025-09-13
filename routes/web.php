<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Rute yang dapat diakses oleh siapa saja.
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/store', [StoreController::class, 'index'])->name('store');
Route::get('/store/{usernameSeller}', [App\Http\Controllers\SellerStoreController::class, 'show'])->name('store.seller');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Menggunakan Auth::routes() untuk mengelola semua rute login/register/logout.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Role-agnostic)
|--------------------------------------------------------------------------
| Rute yang hanya bisa diakses oleh pengguna yang sudah login.
|
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout.show');
Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');



    // Order routes
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderHeader}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{orderHeader}', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/orders/{orderHeader}/received', [App\Http\Controllers\OrderController::class, 'markAsReceived'])->name('orders.mark-received');

    // Profile photo upload
    Route::post('/profile/photo', [App\Http\Controllers\DashboardController::class, 'uploadProfilePhoto'])->name('profile.upload-photo');
});

/*
|--------------------------------------------------------------------------
| Role-specific Routes
|--------------------------------------------------------------------------
| Rute yang hanya bisa diakses oleh role tertentu.
|
*/

// Seller Registration Routes (for non-sellers)
Route::middleware(['auth'])->group(function () {
    Route::get('/seller/register', [App\Http\Controllers\SellerRegistrationController::class, 'showRegistrationForm'])->name('seller.register');
    Route::post('/seller/register', [App\Http\Controllers\SellerRegistrationController::class, 'register']);
});

// Rute untuk role 'seller'
Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/seller/products', [SellerController::class, 'manageProducts'])->name('seller.products.manage');
    Route::get('/seller/products/create', [SellerController::class, 'createProduct'])->name('seller.products.create');
    Route::post('/seller/products', [SellerController::class, 'storeProduct'])->name('seller.products.store');
    Route::get('/seller/products/{product}/edit', [SellerController::class, 'editProduct'])->name('seller.products.edit');
    Route::patch('/seller/products/{product}', [SellerController::class, 'updateProduct'])->name('seller.products.update');
    Route::delete('/seller/products/{product}', [SellerController::class, 'deleteProduct'])->name('seller.products.delete');
    Route::get('/seller/reports', [SellerController::class, 'salesReports'])->name('seller.reports.sales');
    Route::get('/seller/orders', [SellerController::class, 'manageOrders'])->name('seller.orders.manage');
    Route::patch('/seller/orders/{orderHeader}/status', [SellerController::class, 'updateOrderStatus'])->name('seller.orders.update-status');
});

// Removed rating routes as per user request

// Rute untuk role 'developer'
Route::middleware(['auth', 'developer'])->group(function () {
    Route::get('/developer/users', [App\Http\Controllers\DeveloperController::class, 'manageUsers'])->name('developer.users');
    Route::patch('/developer/users/{user}/role', [App\Http\Controllers\DeveloperController::class, 'updateUserRole'])->name('developer.users.update-role');
    Route::delete('/developer/users/{user}', [App\Http\Controllers\DeveloperController::class, 'deleteUser'])->name('developer.users.delete');
    Route::post('/developer/users', [App\Http\Controllers\DeveloperController::class, 'createUser'])->name('developer.users.create');
    Route::delete('/seller/{user}', [App\Http\Controllers\SellerController::class, 'destroy'])->name('seller.destroy');
});
