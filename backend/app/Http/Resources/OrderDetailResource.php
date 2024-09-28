<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'total_amount' => floatval($this->total_amount),
            'order_status' => $this->order_status,
            'shipping_address' => $this->shipping_address,
            'shipping_cost' => floatval( $this->shipping_cost),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
