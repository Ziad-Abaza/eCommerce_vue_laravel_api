<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use App\Http\Resources\ProductDetailResource;
use App\Traits\CrudOperationsTrait;
use App\Traits\HandleFile;

class ProductDetailController extends Controller
{
    use CrudOperationsTrait, HandleFile;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the product details.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $details = $this->getAllWithRelation(new ProductDetails(), ['product', 'cart', 'purchases']);
        return ProductDetailResource::collection($details);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created product detail in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'image'=> 'required|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['size', 'color', 'price', 'discount', 'stock', 'product_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->UploadFiles($request->file('image'), null, 'image');
        }

        $detail = $this->createRecord(new ProductDetails(), $data);

        return new ProductDetailResource($detail);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified product detail.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $detail = $this->findWithRelation(new ProductDetails(), ['product', 'cart', 'image', 'purchases'], $id);
        return new ProductDetailResource($detail);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified product detail in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'image'=> 'required|image|mimes:jpeg,png,jpg|max:2048',
            'discount' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['size', 'color', 'price', 'discount', 'stock', 'product_id']);

        $oldDetails = $this->findById(new ProductDetails(), $id);

        if ($request->hasFile('image')) {
            $data['image'] = $this->updateFile($request, 'image', $oldDetails->image, null, 'image');
        }

        $detail = $this->updateRecord(new ProductDetails(), $id, $data);

        return new ProductDetailResource($detail);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified product detail from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->deleteRecord(new ProductDetails(), $id,  ['image']);
        return response()->json(['message' => 'Product detail deleted successfully'], 200);
    }
}
