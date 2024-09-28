<?php
namespace Database\Factories;

use App\Models\ProductComment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCommentFactory extends Factory
{
    protected $model = ProductComment::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();  // استخدام User موجود مسبقًا
        $product = Product::inRandomOrder()->first();  // استخدام Product موجود مسبقًا

        return [
            'comment' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment_date' => $this->faker->dateTime(),
            'user_id' => $user ? $user->id : User::factory(),
            'product_id' => $product ? $product->id : Product::factory(),
        ];
    }
}


