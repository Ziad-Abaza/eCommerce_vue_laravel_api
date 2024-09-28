<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Http\Resources\OrderDetailResource;
use App\Traits\CrudOperationsTrait;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    use CrudOperationsTrait;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the order details for the current user.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $userId = Auth::id();

        $details = $this->getAllWithRelation(new OrderDetails(), ['user'])
                        ->where('user_id', $userId);

        return OrderDetailResource::collection($details);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created order detail in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'total_amount' => 'required|numeric|min:0',
            'order_status' => 'required|integer|min:0|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['total_amount', 'order_status', 'shipping_address', 'shipping_cost', 'user_id']);
        if ($data['user_id'] != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $detail = $this->createRecord(new OrderDetails(), $data);

        return new OrderDetailResource($detail);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified order detail.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $detail = $this->findWithRelation(new OrderDetails(), ['user'], $id);

        if ($detail->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new OrderDetailResource($detail);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified order detail in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'total_amount' => 'required|numeric|min:0',
            'order_status' => 'required|integer|min:0|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $detail = $this->findWithRelation(new OrderDetails(), ['user'], $id);

        if ($detail->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->only(['total_amount', 'order_status', 'shipping_address', 'shipping_cost', 'user_id']);
        $detail = $this->updateRecord(new OrderDetails(), $id, $data);

        return new OrderDetailResource($detail);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified order detail from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $detail = $this->findWithRelation(new OrderDetails(), ['user'], $id);

        if ($detail->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->deleteRecord(new OrderDetails(), $id);
        return response()->json(['message' => 'Order detail deleted successfully'], 200);
    }
}
