<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Customer\CustomerProductService;
use App\Traits\Images;

class CustomerProductController extends Controller
{
    use Images;
    
    protected $productService;
    public function __construct(CustomerProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function details($slug)
    {
        $product = new ProductResource($this->productService->getProductBySlug($slug));
        $parentCategory = $this->productService->getParentCategoryByProduct($product);
        
        return Inertia::render('Customer/Product/Details', [
            'product' => $product,
            'parentCategory' => $parentCategory
        ]);
    }
}
