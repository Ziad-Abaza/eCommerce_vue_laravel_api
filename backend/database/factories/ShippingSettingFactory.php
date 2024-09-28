<?php

namespace Database\Factories;

use App\Models\ShippingSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingSettingFactory extends Factory
{
    protected $model = ShippingSetting::class;

    public function definition()
    {
        return [
            'area' => $this->faker->city(),
            'cost' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}

