<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/categories', [BookController::class, 'categories'])->name('books.categories');
Route::get('/search', [BookController::class, 'search'])->name('books.search');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// API Routes for AJAX
Route::prefix('api')->group(function () {
    Route::post('/cart/add', [CartController::class, 'apiAdd'])->name('api.cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'apiRemove'])->name('api.cart.remove');
});