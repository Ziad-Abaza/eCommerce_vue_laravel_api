<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();  // استخدام User موجود مسبقًا
        $productDetail = ProductDetails::inRandomOrder()->first();  // استخدام ProductDetails موجود مسبقًا

        return [
            'Product_id' => Product::inRandomOrder()->first()??Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'user_id' => $user ? $user->id : User::factory(),
            'product_detail_id' => $productDetail ? $productDetail->id : ProductDetails::factory(),
        ];
    }
}


