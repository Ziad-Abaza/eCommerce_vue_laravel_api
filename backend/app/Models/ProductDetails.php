<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'size', 'color', 'price', 'image', 'discount', 'stock', 'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

        public function cart()
    {
        return $this->hasMany(Cart::class, 'product_detail_id');
    }
    
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_detail_id');
    }
}
