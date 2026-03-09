<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/categories', [BookController::class, 'categories'])->name('books.categories');
Route::get('/search', [BookController::class, 'search'])->name('books.search');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');