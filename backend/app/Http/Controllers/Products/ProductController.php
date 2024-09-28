<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Traits\CrudOperationsTrait;
use App\Traits\HandleFile;

class ProductController extends Controller
{
    use CrudOperationsTrait, HandleFile;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the products.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $products = $this->getAllWithRelation(new Product(), ['vendor', 'categories', 'details', 'comments', 'favorites']);

        return ProductResource::collection($products);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created product in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand' =>  'nullable|string',
            'vendor_id' => 'required|exists:vendors,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['product_name', 'description', 'vendor_id']);
        $product = $this->createRecord(new Product(), $data);

        return new ProductResource($product);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified product.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $product = $this->findWithRelation(new Product(), ['vendor', 'categories', 'details', 'comments', 'favorites'], $id);
        return new ProductResource($product);
    }

    /*
    |--------------------------------------------------------------------------
    | Display a limited listing of products (limit 30).
    |--------------------------------------------------------------------------
    */
    public function limitedProducts()
    {
        $products = Product::with(['vendor', 'categories', 'details', 'comments', 'favorites'])
                            ->paginate(30);

        return response()->json([
        'data' => ProductResource::collection($products),
        'meta' => [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'next_page_url' => $products->nextPageUrl(),
            'prev_page_url' => $products->previousPageUrl(),
        ]
    ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified product in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand' =>  'nullable|string',
            'vendor_id' => 'required|exists:vendors,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['product_name', 'description', 'vendor_id']);
        $product = $this->updateRecord(new Product(), $id, $data);

        return new ProductResource($product);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified product from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->deleteRecord(new Product(), $id);
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
