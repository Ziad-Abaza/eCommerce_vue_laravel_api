<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => $this->is_active,
            // 'notifications' => NotificationResource::collection($this->whenLoaded('notifications')),
            // 'comments' => ProductCommentResource::collection($this->whenLoaded('comments')),
            'orders' => OrderDetailResource::collection($this->whenLoaded('orders')),
            // 'cart' => CartResource::collection($this->whenLoaded('cart')),
            'purchases' => PurchaseResource::collection($this->whenLoaded('purchases')),
            'favorites' => ProductResource::collection($this->whenLoaded('favorites')),
        ];
    }
}
