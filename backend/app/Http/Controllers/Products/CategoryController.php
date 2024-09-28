<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Traits\CrudOperationsTrait;

class CategoryController extends Controller
{
    use CrudOperationsTrait;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the categories.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $categories = $this->getAllWithRelation(new Category(), ['products']);
        return CategoryResource::collection($categories);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created category in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'category_name' => 'required|string|max:255|unique:categories',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['category_name']);
        $category = $this->createRecord(new Category(), $data);

        return new CategoryResource($category);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified category.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $category = $this->findWithRelation(new Category(), ['products'], $id);
        return new CategoryResource($category);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified category in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'category_name' => 'required|string|max:255|unique:categories,category_name,'.$id,
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['category_name']);
        $category = $this->updateRecord(new Category(), $id, $data);

        return new CategoryResource($category);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified category from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->deleteRecord(new Category(), $id);
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}