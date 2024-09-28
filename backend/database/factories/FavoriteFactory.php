<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();  // استخدام User موجود مسبقًا
        $product = Product::inRandomOrder()->first();  // استخدام Product موجود مسبقًا

        return [
            'user_id' => $user ? $user->id : User::factory(),
            'product_id' => $product ? $product->id : Product::factory(),
        ];
    }
}

