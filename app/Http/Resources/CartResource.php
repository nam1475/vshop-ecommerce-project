<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Traits\CartTrait;

class CartResource extends JsonResource
{
    use CartTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(!empty($this->resource)){
            [$products, $cartItems] = $this->resource;
            return [
                'count' => $this->countCartItems(),
                'total' => $products->reduce(fn (?float $carry, Product $product) => $carry + $product->price * $cartItems[$product->id]['quantity']),
                'items' => $cartItems,
                'products' => ProductResource::collection($products),
            ];
        }
        return [];
    }
}
