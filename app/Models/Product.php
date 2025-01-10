<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'name', 'description', 'quantity', 'price', 'photo'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    
    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
}
    
}