<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    

    protected $fillable = [

        "name",
        "description",
        "price",
        "category_id",
        "stock",
        "image"

    ] ;





    public function category()
    {
        return $this->belongsTo(Category::class); // Each product belongs to a category
    }

    // public function cart()
    // {
    //     return $this->hasMany(Cart::class); // Each product belongs to a cart
    // }
}
