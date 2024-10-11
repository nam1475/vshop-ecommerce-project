<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Images;

class ProductResource extends JsonResource
{
    use Images;
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
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            // 'images' => $this->whenLoaded('images', function () {
            //     return ProductImageResource::collection($this->images);
            // }),
            'images' => $this->getMedia('product_images')->map(function ($mediaItem) {
                return $mediaItem->getUrl();
            }),
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand_id' => $this->brand_id,
            'brand' => new BrandResource($this->whenLoaded('brand')),
        ];
    }
}
