<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebUserController;
use App\Http\Controllers\WebDashboardController;
use App\Http\Controllers\WebShopController;
use App\Http\Controllers\WebProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureOwner;
use App\Models\User;


Route::get('/login', [WebUserController::class, 'create'])->name('login');
Route::post('/login', [WebUserController::class, 'login'])->name('wlogin');
Route::post('/logout', [WebUserController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', [WebDashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/shop/store', [WebShopController::class, 'store'])->name('shop.store');
    Route::post('/shop/update/{id}', [WebShopController::class, 'update'])->name('shop.update');
    Route::get('/shop/edit/{id}', [WebShopController::class, 'edit'])->name('shop.edit');
    Route::post('/shop/delete', [WebShopController::class, 'destroy'])->name('shop.destroy');
    Route::get('/orders', [WebShopController::class, 'showorders'])->name('orders.index');

});

Route::middleware('auth')->group(function () {
    Route::post('/product/store', [WebProductController::class, 'store'])->name('product.store');
    Route::post('/product/update/{id}', [WebProductController::class, 'update'])->name('product.update');
    Route::get('/product/edit/{id}', [WebProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/delete/{id}', [WebProductController::class, 'destroy'])->name('product.destroy');
});