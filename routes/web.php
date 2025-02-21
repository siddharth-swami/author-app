<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('api.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/author/{id}', [AuthorController::class, 'show'])->name('author.show');
    Route::get('/add-author', [AuthorController::class, 'create'])->name('author.create');
    Route::post('/add-author', [AuthorController::class, 'store'])->name('author.store');
    Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy');

    Route::delete('/book/{id}', [BookController::class, 'destroy'])->name('book.destroy');
    Route::get('/add-book', [BookController::class, 'create'])->name('book.create');
    Route::post('/add-book', [BookController::class, 'store']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


