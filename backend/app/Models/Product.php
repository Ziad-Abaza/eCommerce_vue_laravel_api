<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

        protected $fillable = [
        'product_name', 'brand', 'description', 'vendor_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_has_products', 'product_id', 'category_id');
    }

    public function details()
    {
        return $this->hasMany(ProductDetails::class, 'product_id');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id');
    }

        public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }

    // public function favorites()
    // {
    //     return $this->hasMany(Favorite::class, 'product_id', 'user_id');
    // }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

}
