<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'total_price' => $this->total_price,
            'status' => $this->status,
            'session_id' => $this->session_id,
            'customer_address_id' => $this->customer_address_id,
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'customer_address' => new CustomerAddressResource($this->whenLoaded('customerAddress')),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at->format('H:i | d.m.Y'),
            'updated_at' => $this->updated_at->format('H:i | d.m.Y'),

        ];
    }
}
