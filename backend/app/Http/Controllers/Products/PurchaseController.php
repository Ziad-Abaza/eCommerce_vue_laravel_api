<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Resources\PurchaseResource;
use App\Traits\CrudOperationsTrait;

class PurchaseController extends Controller
{
    use CrudOperationsTrait;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the purchases.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $purchases = $this->getAllWithRelation(new Purchase(), ['user', 'productDetails']);
        return PurchaseResource::collection($purchases);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created purchase in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'product_detail_id' => 'required|exists:product_details,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['quantity', 'total_price', 'purchase_date', 'user_id', 'product_detail_id']);
        $purchase = $this->createRecord(new Purchase(), $data);

        return new PurchaseResource($purchase);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified purchase.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $purchase = $this->findWithRelation(new Purchase(), ['user', 'productDetails'], $id);
        return new PurchaseResource($purchase);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified purchase in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'product_detail_id' => 'required|exists:product_details,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['quantity', 'total_price', 'purchase_date', 'user_id', 'product_detail_id']);
        $purchase = $this->updateRecord(new Purchase(), $id, $data);

        return new PurchaseResource($purchase);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified purchase from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->deleteRecord(new Purchase(), $id);
        return response()->json(['message' => 'Purchase deleted successfully'], 200);
    }
}
