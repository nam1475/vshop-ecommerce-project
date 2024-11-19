<?php

namespace App\Http\Services\Customer;

use App\Models\Category;
use App\Models\Product;

class CustomerProductService
{
    public function getProductBySlug($slug)
    {
        return Product::with('category')->where('slug', $slug)->first();
    }

    public function getProductsWithPagination()
    {
        return Product::paginate(8);
    }

    public function getParentCategoryByProduct($product)
    {
        return Category::find($product->category->parent_id);
    }
    
    public function getProductIdsByCategory($category)
    {
        // Tạo một mảng để chứa tất cả các ID sản phẩm
        $productIds = collect($category->products->pluck('id'));

        // Lặp qua tất cả các danh mục con và gọi đệ quy
        foreach ($category->childrenRecursive as $childCategory) {
            $productIds = $productIds->merge($this->getProductIdsByCategory($childCategory));
        }

        return $productIds;
    }
}