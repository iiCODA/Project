<?php


use App\Models\User;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureOwner;



Route::post('/dashboard/login', [DashboardController::class, 'wlogin'])->name('login.post');
Route::post('/dashboard/logout', [DashboardController::class, 'logout'])->name('logout');

Route::middleware('auth:sanctum',EnsureOwner::class)->group(function () {

Route::post('/dashboard/promote', [DashboardController::class, 'promote'])->name('promote');
Route::post('/dashboard/unpromote/{id}', [DashboardController::class, 'unpromote'])->name('unpromote');
Route::get('/dashboard/admins', [DashboardController::class, 'adminIndex'])->name('admin.index');
Route::get('/dashboard/users', [DashboardController::class, 'userIndex'])->name('user.index');
});

Route::get('/allusers', [UserController::class, 'index']);
Route::get('/users', [UserController::class, 'show'])->middleware('auth:sanctum');

Route::post('/users', [UserController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/usersEdit', [UserController::class, 'update'])->middleware('auth:sanctum');

    
    Route::delete('/users', [UserController::class, 'destroy'])->middleware('auth:sanctum');

});


Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');


//shop  routes:

Route::get('/shops', [ShopController::class, 'index']);
Route::get('/shops/{id}', [ShopController::class, 'show_with_products']);

Route::middleware('auth:sanctum',AdminMiddleware::class)->group(function () {
    Route::get('/shop', [ShopController::class, 'show']); 
    Route::post('/shop', [ShopController::class, 'store']); 
    Route::post('/shopEdit', [ShopController::class, 'update']);
    Route::delete('/shop', [ShopController::class, 'destroy']); 
    Route::get('/shop-orders', [ShopController::class, 'showorders']);
});

Route::post('/search', [ShopController::class, 'search']);


 
 

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/my-products', [ProductController::class, 'myProducts']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']); 
    Route::post('/cart', [CartController::class, 'add']); 
    Route::put('/cart/{cartItemId}', [CartController::class, 'update']); 
    Route::delete('/cart/{cartItemId}', [CartController::class, 'delete']); 
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::post('/orders/submit-cart', [OrderController::class, 'submitCart']);
    
    Route::get('/orders', [OrderController::class, 'index']); 
    Route::put('/orders/{orderId}', [OrderController::class, 'updateOrder']);
    Route::delete('/orders/{orderId}', [OrderController::class, 'deleteOrder']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);


});


Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'getNotifications']);
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead']); 
    Route::delete('/{id}', [NotificationController::class, 'deleteNotification']); 
});


//favorates
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favorites/{productId}', [FavoriteController::class, 'addFavorite']); 
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'removeFavorite']); 
    Route::get('/favorites', [FavoriteController::class, 'getFavorites']);
});

