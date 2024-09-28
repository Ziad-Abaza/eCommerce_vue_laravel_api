<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'quantity' =>intval( $this->quantity),
            'total_price' => floatval($this->total_price),
            'purchase_date' => $this->purchase_date,
            'user' => new UserResource($this->whenLoaded('user')),
            'product_detail' => new ProductDetailResource($this->whenLoaded('productDetail')),
        ];
    }
}
