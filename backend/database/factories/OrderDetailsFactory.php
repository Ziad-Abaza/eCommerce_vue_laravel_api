<?php

namespace Database\Factories;

use App\Models\OrderDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailsFactory extends Factory
{
    protected $model = OrderDetails::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();  // استخدام User موجود مسبقًا

        return [
            'total_amount' => $this->faker->randomFloat(2, 50, 1000),
            'order_status' => $this->faker->numberBetween(0, 1),
            'shipping_address' => $this->faker->address(),
            'shipping_cost' => $this->faker->randomFloat(2, 5, 50),
            'user_id' => $user ? $user->id : User::factory(),
        ];
    }
}

