<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'image' => $this->faker->imageUrl(),
            'password' => bcrypt('password'),
        ];
    }
}

