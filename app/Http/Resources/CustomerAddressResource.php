<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(!empty($this->resource)){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'address' => $this->address,
                'ward' => $this->ward,
                'district' => $this->district,
                'province' => $this->province,
                'phone' => $this->phone,
                'is_main' => $this->is_main,
                'customer_id' => $this->customer_id,
                'customer' => new CustomerResource($this->whenLoaded('customer')),
            ];
        }
        return [];
    }
}
