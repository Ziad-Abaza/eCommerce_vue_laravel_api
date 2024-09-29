<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use App\Models\ProductDetails;
use App\Traits\CrudOperationsTrait;
use App\Traits\HandleFile;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use CrudOperationsTrait, HandleFile;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the carts for the current user.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();

        $carts = $this->getAllWithRelation(new Cart(), ['user', 'product', 'productDetail'])
                      ->where('user_id', $user->id);
        return CartResource::collection($carts);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created cart item in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
            'product_detail_id' => 'required|exists:product_details,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $userId = Auth::id();

        $data = $request->only(['quantity', 'product_id', 'product_detail_id']);
        $data['user_id'] = $userId;
        $cart = $this->createRecord(new Cart(), $data);

        return new CartResource($cart);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified cart item.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $cart = $this->findWithRelation(new Cart(), ['user', 'product'], $id);

        if ($cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new CartResource($cart);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified cart item in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
            'product_detail_id' => 'required|exists:product_details,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $userId = Auth::id();

        $cart = $this->findWithRelation(new Cart(), ['user', 'product', 'productDetail'], $id);

        if ($cart->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->only(['quantity', 'product_id', 'product_detail_id']);
        $data['user_id'] = $userId;
        $cart = $this->updateRecord(new Cart(), $id, $data);

        return new CartResource($cart);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified cart item from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $cart = $this->findWithRelation(new Cart(), ['user', 'product', 'ProductDetail'], $id);

        if ($cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->deleteRecord(new Cart(), $id);
        return response()->json(['message' => 'Cart item deleted successfully'], 200);
    }
}
