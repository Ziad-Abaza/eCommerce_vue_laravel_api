<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity', 'total_price', 'purchase_date', 'user_id', 'product_detail_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productDetails()
    {
        return $this->belongsTo(ProductDetails::class, 'product_detail_id');
    }
}
