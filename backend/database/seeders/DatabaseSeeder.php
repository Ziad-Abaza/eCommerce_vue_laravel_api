<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\Favorite;
use App\Models\Cart;
use App\Models\Purchase;
use App\Models\ProductComment;
use App\Models\OrderDetails;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء المستخدمين
        $users = User::factory(10)->create();

        // إنشاء الإشعارات وربطها بالمستخدمين الحاليين
        $notifications = Notification::factory(20)->create()->each(function ($notification) use ($users) {
            $notification->users()->attach($users->random()->id);
        });

        // إنشاء إعدادات الشحن
        \App\Models\ShippingSetting::factory(5)->create();

        // إنشاء Vendors
        $vendors = Vendor::factory(10)->create();

        // إنشاء Categories
        $categories = Category::factory(10)->create();

        // إنشاء Products وربطها بـ Vendors و Categories
        $products = Product::factory(50)->create()->each(function ($product) use ($vendors, $categories) {
            $product->vendor()->associate($vendors->random())->save();
            $product->categories()->attach($categories->random()->id);
        });

        // إنشاء ProductDetails وربطها بـ Products
        $productDetails = ProductDetails::factory(100)->create()->each(function ($productDetail) use ($products) {
            $productDetail->product()->associate($products->random())->save();
        });

        // إنشاء Carts وربطها بـ Users و ProductDetails
        Cart::factory(30)->create()->each(function ($cart) use ($users, $products) {
            $cart->user()->associate($users->random())->save();
            $cart->product()->associate($products->random())->save();
        });

        // إنشاء Favorite وربطها بـ Users 
        Favorite::factory(30)->create()->each(function ($fav) use ($users, $products) {
            $fav->user()->associate($users->random())->save();
            $fav->product()->associate($products->random())->save();
        });

        // إنشاء Purchases وربطها بـ Users و ProductDetails
        Purchase::factory(30)->create()->each(function ($purchase) use ($users, $productDetails) {
            $purchase->user()->associate($users->random())->save();
            $purchase->productDetails()->associate($productDetails->random())->save();
        });

        // إنشاء تعليقات على المنتجات وربطها بـ Users و Products
        ProductComment::factory(50)->create()->each(function ($comment) use ($users, $products) {
            $comment->user()->associate($users->random())->save();
            $comment->product()->associate($products->random())->save();
        });

        // إنشاء OrderDetails وربطها بـ Users
        OrderDetails::factory(20)->create()->each(function ($orderDetail) use ($users) {
            $orderDetail->user()->associate($users->random())->save();
        });
    }
}

