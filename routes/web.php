<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

use App\Http\Middleware\EnsureOwner;
use App\Models\User;


Route::post('/promote', [UserController::class, 'promote'])->name('promote')->middleware(EnsureOwner::class);

Route::post('/unpromote/{id}', [UserController::class, 'unpromote'])->middleware(EnsureOwner::class)->name('unpromote');

Route::get('/', [UserController::class, 'adminIndex'])->name('admin.index')->middleware(EnsureOwner::class);





Route::view('/login', 'login')->name('login');
Route::post('/login', [UserController::class, 'wlogin'])->name('login.post');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // Or wherever you'd like to redirect after logging out
})->name('logout');
