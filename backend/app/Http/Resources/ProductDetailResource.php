<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'size' => $this->size,
            'color' => $this->color,
            'image' => $this->image,
            'price' => floatval($this->price),
            'discount' => $this->discount,
            'stock' => intval($this->stock),
            // 'product' => new ProductResource($this->whenLoaded('product')),
            // 'purchases' => PurchaseResource::collection($this->whenLoaded('purchases')),
        ];
    }
}
