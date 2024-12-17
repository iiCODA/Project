<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory,Notifiable;
    
    protected $table = 'users';

    protected $fillable = [
        'phone',
        'first_name',
        'last_name',
        'location', 
        'profile_photo',  
    ];

    // Automatically create a cart when a user is created
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->cart()->create(); // Create a cart for the user
        });
    }

    // Relationship with Shop
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    // Relationship with Cart
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
