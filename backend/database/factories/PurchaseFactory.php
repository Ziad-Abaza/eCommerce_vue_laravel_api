<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\ProductDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => $this->faker->randomFloat(2, 20, 500),
            'purchase_date' => $this->faker->dateTime(),
            'user_id' => User::factory(),
            'Product_Detail_id' => ProductDetails::factory(),
        ];
    }
}

