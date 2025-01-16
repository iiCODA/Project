<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;
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
