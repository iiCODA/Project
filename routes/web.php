<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebUserController;
use App\Http\Controllers\WebDashboardController;
use App\Http\Controllers\WebShopController;
use App\Http\Controllers\WebProductController;
use App\Http\Controllers\OwnerController;
use App\Http\Middleware\AdminGuard;
use App\Http\Middleware\OwnerGuard;
use App\Http\Middleware\RedirectIfOwnerUnauthenticated;
use Illuminate\Support\Facades\Auth;

// Admin Authentication and Dashboard
Route::get('/login', [WebUserController::class, 'create'])->name('login');
Route::post('/login', [WebUserController::class, 'login'])->name('wlogin');
Route::post('/logout', [WebUserController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Dashboard
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [WebDashboardController::class, 'index'])->name('dashboard');

    // Shop Management
    Route::post('/shop/store', [WebShopController::class, 'store'])->name('shop.store');
    Route::post('/shop/update/{id}', [WebShopController::class, 'update'])->name('shop.update');
    Route::get('/shop/edit/{id}', [WebShopController::class, 'edit'])->name('shop.edit');
    Route::post('/shop/delete', [WebShopController::class, 'destroy'])->name('shop.destroy');
    Route::get('/orders', [WebShopController::class, 'showorders'])->name('orders.index');

    // Product Management
    Route::post('/product/store', [WebProductController::class, 'store'])->name('product.store');
    Route::post('/product/update/{id}', [WebProductController::class, 'update'])->name('product.update');
    Route::get('/product/edit/{id}', [WebProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/delete/{id}', [WebProductController::class, 'destroy'])->name('product.destroy');
});

// Owner Login and Dashboard
Route::get('/owner/login', [OwnerController::class, 'showLoginForm'])->name('owner.login'); // Show login form
Route::post('/owner/login', [OwnerController::class, 'login'])->name('owner.login.submit'); // Handle login


// Owner Dashboard
Route::middleware([RedirectIfOwnerUnauthenticated::class])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/users', [OwnerController::class, 'userManagement'])->name('owner.users');
    Route::post('/owner/promote', [OwnerController::class, 'promote'])->name('owner.promote');
    Route::post('/owner/unpromote', [OwnerController::class, 'unpromote'])->name('owner.unpromote');
    Route::post('/owner/logout', [OwnerController::class, 'logout'])->name('owner.logout');
    Route::post('/owner/block', [OwnerController::class, 'block'])->name('owner.block');
    Route::post('/owner/restore', [OwnerController::class, 'restore'])->name('owner.restore');
    Route::post('/owner/unpromote', [OwnerController::class, 'unpromote'])->name('owner.unpromote');
    Route::post('/owner/promote', [OwnerController::class, 'promote'])->name('owner.promote');
});
