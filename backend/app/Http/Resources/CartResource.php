<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $productDetails = $this->product->details;

        return [
            'id' => $this->id,
            'quantity' => intval($this->quantity),
            'user' => new UserResource($this->whenLoaded('user')),
            'product' => [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->product_name,
                    'description' => $this->product->description,
                    'brand' => $this->product->brand,
                    'vendor' => $this->product->vendor->name ?? null, 
                    'categories' => $this->product->categories->pluck('category_name'),
                    'details' => $this->whenLoaded('productDetail', function () {
                    return [
                        'id'  => $this->productDetail->id,
                        'stock' => intval($this->productDetail->stock),
                        'discount' => $this->productDetail->discount,
                        'image' => $this->productDetail->image,
                        'price' => floatval($this->productDetail->price),
                        'color' => $this->productDetail->color,
                        'size' => $this->productDetail->size,
                    ];
                }),
            ],
        ];
    }
}
