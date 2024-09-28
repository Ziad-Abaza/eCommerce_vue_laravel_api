<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Resources\FavoriteResource;
use App\Traits\CrudOperationsTrait;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    use CrudOperationsTrait;

    /*
    |--------------------------------------------------------------------------
    | Display a listing of the favorites for the current user.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();

        $favorites = $this->getAllWithRelation(new Favorite(), ['user', 'product'])
                  ->where('user_id', $user->id);  

        return FavoriteResource::collection($favorites);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created favorite in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'product_id' => 'required|exists:products,id',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $userId = Auth::id();

        $data = $request->only(['product_id']);
        $data['user_id'] = $userId;
        $favorite = $this->createRecord(new Favorite(), $data);

        return new FavoriteResource($favorite);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified favorite.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $favorite = $this->findWithRelation(new Favorite(), ['user', 'product'], $id);

        if ($favorite->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new FavoriteResource($favorite);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Remove the specified favorite from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $favorite = $this->findWithRelation(new Favorite(), ['user', 'product'], $id);

        if ($favorite->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->deleteRecord(new Favorite(), $id);
        return response()->json(['message' => 'Favorite deleted successfully'], 200);
    }
}
