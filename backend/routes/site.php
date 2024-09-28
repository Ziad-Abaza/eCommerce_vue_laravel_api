<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductCommentController ;
use App\Http\Controllers\Products\ProductDetailController;
use App\Http\Controllers\Products\NotificationController ;
use App\Http\Controllers\Products\OrderDetailController ;
use App\Http\Controllers\Products\PurchaseController;
use App\Http\Controllers\Products\FavoriteController;
use App\Http\Controllers\Products\CategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\VendorController;
use App\Http\Controllers\Products\CartController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Site Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::prefix('profile')->group(function () {
    Route::get('/', [UserController::class, 'profile']);
    Route::post('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/limited', [ProductController::class, 'limitedProducts']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::get('/{id}', [CartController::class, 'show']);
    Route::post('/', [CartController::class, 'store']);
    Route::post('/{id}', [CartController::class, 'update']);
    Route::delete('/{id}', [CartController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Product Comment Routes
|--------------------------------------------------------------------------
*/

Route::prefix('comment')->group(function () {
    Route::get('/', [ProductCommentController::class, 'index']);
    Route::get('/{id}', [ProductCommentController::class, 'show']);
    Route::post('/', [ProductCommentController::class, 'store']);
    Route::post('/{id}', [ProductCommentController::class, 'update']);
    Route::delete('/{id}', [ProductCommentController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Product Detail Routes
|--------------------------------------------------------------------------
*/

Route::prefix('product-details')->group(function () {
    Route::get('/', [ProductDetailController::class, 'index']);
    Route::get('/{id}', [ProductDetailController::class, 'show']);
    Route::post('/', [ProductDetailController::class, 'store']);
    Route::post('/{id}', [ProductDetailController::class, 'update']);
    Route::delete('/{id}', [ProductDetailController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Order Detail Routes
|--------------------------------------------------------------------------
*/

Route::prefix('order-details')->group(function () {
    Route::get('/', [OrderDetailController::class, 'index']);
    Route::get('/{id}', [OrderDetailController::class, 'show']);
    Route::post('/', [OrderDetailController::class, 'store']);
    Route::post('/{id}', [OrderDetailController::class, 'update']);
    Route::delete('/{id}', [OrderDetailController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Purchase Routes
|--------------------------------------------------------------------------
*/

Route::prefix('purchase')->group(function () {
    Route::get('/', [PurchaseController::class, 'index']);
    Route::get('/{id}', [PurchaseController::class, 'show']);
    Route::post('/', [PurchaseController::class, 'store']);
    Route::post('/{id}', [PurchaseController::class, 'update']);
    Route::delete('/{id}', [PurchaseController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Favorite Routes
|--------------------------------------------------------------------------
*/

Route::prefix('favorite')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::get('/{id}', [FavoriteController::class, 'show']);
    Route::post('/', [FavoriteController::class, 'store']);
    Route::delete('/{id}', [FavoriteController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::post('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/

Route::prefix('notification')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/{id}', [NotificationController::class, 'show']);
    Route::post('/', [NotificationController::class, 'store']);
    Route::post('/{id}', [NotificationController::class, 'update']);
    Route::delete('/clear-all', [NotificationController::class, 'clearAll']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->group(function () {
    Route::get('/', [VendorController::class, 'index']);
    Route::get('/{id}', [VendorController::class, 'show']);
    Route::post('/', [VendorController::class, 'store']);
    Route::post('/{id}', [VendorController::class, 'update']);
    Route::delete('/{id}', [VendorController::class, 'destroy']);
});