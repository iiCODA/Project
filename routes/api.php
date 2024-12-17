<?php


use App\Models\User;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
//user routes:

// Routes for listing and viewing users
Route::get('/allusers', [UserController::class, 'index']);
Route::get('/users', [UserController::class, 'show'])->middleware('auth:sanctum');

// Routes for creating users (No authentication needed for creating new users)
Route::post('/users', [UserController::class, 'store']);

// Protect routes that modify user data
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/users', [UserController::class, 'update'])->middleware('auth:sanctum');
    // Ensure user can only update their own data
    
    Route::delete('/users', [UserController::class, 'destroy'])->middleware('auth:sanctum');

});

// Login and logout routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');


//shop  routes:

Route::get('/shops', [ShopController::class, 'index']);
Route::get('/shops/{id}', [ShopController::class, 'show_with_products']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/shop', [ShopController::class, 'show']); // Show logged-in user's shop
    Route::post('/shop', [ShopController::class, 'store']); // Create a shop
    Route::put('/shop', [ShopController::class, 'update']); // Update a shop
    Route::delete('/shop', [ShopController::class, 'destroy']); // Delete a shop
});

//search bar for the products and the shops
Route::post('/search', [ShopController::class, 'search']);


//products routes 
 

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/my-products', [ProductController::class, 'myProducts']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

});

//cart routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']); // View cart
    Route::post('/cart', [CartController::class, 'add']); // Add product to cart
    Route::put('/cart/{cartItemId}', [CartController::class, 'update']); // Update product quantity
    Route::delete('/cart/{cartItemId}', [CartController::class, 'delete']); // Delete product from cart
});


//order controller
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::post('/orders/submit-cart', [OrderController::class, 'submitCart']);
    
    // Add the route to view all orders for the authenticated user
    Route::get('/orders', [OrderController::class, 'index']);  // This fetches all orders
    Route::put('/orders/{orderId}', [OrderController::class, 'updateOrder']);
    Route::delete('/orders/{orderId}', [OrderController::class, 'deleteOrder']);

});


//notification controller


Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'getNotifications']); // Fetch all notifications
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead']); // Mark notifications as read
    Route::delete('/{id}', [NotificationController::class, 'deleteNotification']); // Delete a specific notification
});

