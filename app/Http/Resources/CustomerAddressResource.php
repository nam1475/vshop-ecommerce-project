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
                'type' => $this->type,
                'address' => $this->address,
                'ward' => $this->ward,
                'district' => $this->district,
                'province' => $this->province,
                'phone' => $this->phone,
                'zip_code' => $this->zip_code,
                'is_main' => $this->is_main,
                'country_code' => $this->country_code,
                'customer_id' => $this->customer_id,
                'customer' => new CustomerResource($this->whenLoaded('customer')),
            ];
        }
        return [];
    }
}
