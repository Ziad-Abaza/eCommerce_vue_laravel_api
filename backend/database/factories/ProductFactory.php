<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $vendor = Vendor::inRandomOrder()->first();  // استخدام Vendor موجود مسبقًا
        $brand = ['adidas', 'nike', 'puma', 'reebok', 'new balance'];

        return [
            'product_name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'brand' => $brand[array_rand($brand)],
            'Vendor_id' => $vendor ? $vendor->id : Vendor::factory(),  // ربط بـ Vendor موجود أو إنشاء جديد
        ];
    }
}


