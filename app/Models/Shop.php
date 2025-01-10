<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'location', 'user_id','shop_photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
         return $this->hasMany(Product::class);
    } 


}
