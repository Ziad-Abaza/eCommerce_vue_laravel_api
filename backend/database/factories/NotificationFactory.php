<?php
namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'type' => $this->faker->word(),
        ];
    }
}
