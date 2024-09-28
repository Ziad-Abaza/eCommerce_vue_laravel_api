<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $favorite = $this->favorites()->where('user_id', Auth::id())->first();
        // $favorite = $this->favorites()->where('user_id', 1)->first(); //test

        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'description' => $this->description,
            'brand'  => $this->brand,
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'details' => ProductDetailResource::collection($this->whenLoaded('details')),
            'comments' => ProductCommentResource::collection($this->whenLoaded('comments')),
            'favorites' => FavoriteResource::collection($this->whenLoaded('favorites')),  
        ];
    }
}
