<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use App\Http\Resources\ProductCommentResource;
use App\Traits\CrudOperationsTrait;

class ProductCommentController extends Controller
{
    use CrudOperationsTrait;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the product comments.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $comments = $this->getAllWithRelation(new ProductComment(), ['user', 'product']);
        return ProductCommentResource::collection($comments);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created product comment in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['comment', 'rating', 'user_id', 'product_id']);
        $comment = $this->createRecord(new ProductComment(), $data);

        return new ProductCommentResource($comment);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified product comment.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $comment = $this->findWithRelation(new ProductComment(), ['user', 'product'], $id);
        return new ProductCommentResource($comment);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified product comment in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['comment', 'rating', 'user_id', 'product_id']);
        $comment = $this->updateRecord(new ProductComment(), $id, $data);

        return new ProductCommentResource($comment);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified product comment from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->deleteRecord(new ProductComment(), $id);
        return response()->json(['message' => 'Product comment deleted successfully'], 200);
    }
}
