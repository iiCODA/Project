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



use App\Http\Middleware\AdminMiddleware;



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

