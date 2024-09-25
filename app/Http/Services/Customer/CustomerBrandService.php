<?php

namespace App\Http\Services\Customer;

use App\Models\Brand;

class CustomerBrandService
{
    public function getBrandsByProducts($products)
    {   
        return Brand::whereIn('id', $products->pluck('brand_id')->unique())->withCount('products')->get();
    }


}