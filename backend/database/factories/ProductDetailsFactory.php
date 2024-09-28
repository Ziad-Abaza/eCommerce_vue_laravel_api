<?php

namespace Database\Factories;

use App\Models\ProductDetails;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDetailsFactory extends Factory
{
    protected $model = ProductDetails::class;

    public function definition()
    {
        $product = Product::inRandomOrder()->first();  // استخدام Product موجود مسبقًا

        return [
            'size' => $this->faker->word(),
            'color' => $this->faker->colorName(),
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'stock' => $this->faker->numberBetween(1, 100),
            'Product_id' => $product ? $product->id : Product::factory(),  // ربط بـ Product موجود أو إنشاء جديد
        ];
    }
}


