<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VnPayController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/categories', [BookController::class, 'categories'])->name('books.categories');
Route::get('/search', [BookController::class, 'search'])->name('books.search');

//Filter Routes
Route::get('/filter', [BookController::class, 'filter'])->name('books.filter');

// Cart Routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // VNPAY Routes
    Route::post('/vnpay/payment', [VnPayController::class, 'vnpay_payment'])->name('vnpay.payment');
    Route::get('/vnpay/return', [VnPayController::class, 'vnpay_return'])->name('vnpay.return');
    Route::get('/vnpay/success/{id}', [VnPayController::class, 'vnpay_success'])->name('vnpay.success');

    // API Routes for AJAX inside auth group
    Route::prefix('api')->group(function () {
        Route::post('/cart/add', [CartController::class, 'apiAdd'])->name('api.cart.add');
        Route::delete('/cart/remove/{id}', [CartController::class, 'apiRemove'])->name('api.cart.remove');
    });
});

// Login & Register Routes
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login']);

    Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register']);
});

// Logout Route
Route::post('/dang-xuat', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Quản lý người dùng
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    
    // Quản lý sản phẩm
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('products.store');
    
    // Quản lý đơn hàng
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::post('/orders/update/{id}', [AdminController::class, 'updateOrderStatus'])->name('orders.update');
});