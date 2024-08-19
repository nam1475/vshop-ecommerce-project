<?php

namespace App\Http\Resources;

use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

class CartResource extends JsonResource
{
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
                'count' => CartHelper::countCartItems(),
                'total' => $products->reduce(fn (?float $carry, Product $product) => $carry + $product->price * $cartItems[$product->id]['quantity']),
                'items' => $cartItems,
                'products' => ProductResource::collection($products),
            ];
        }
        return [];
    }
}
