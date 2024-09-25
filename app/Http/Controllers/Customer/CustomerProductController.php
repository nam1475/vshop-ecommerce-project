<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Customer\CustomerProductService;

class CustomerProductController extends Controller
{
    protected $productService;

    public function __construct(CustomerProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function details($slug)
    {
        // dd($this->getProductBySlug($slug));
        return Inertia::render('Customer/Product/Details', [
            'product' => $this->productService->getProductBySlug($slug),
        ]);
    }
}
