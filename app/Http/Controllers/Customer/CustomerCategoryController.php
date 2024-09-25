<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Services\Customer\CustomerBrandService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\HelperTrait;
use App\Http\Services\Customer\CustomerCategoryService;
use App\Http\Services\Customer\CustomerProductService;

class CustomerCategoryController extends Controller
{
    use HelperTrait;

    protected $categoryService;
    protected $productService;
    protected $brandService;
    public function __construct(CustomerCategoryService $categoryService, CustomerProductService $productService, CustomerBrandService $brandService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->brandService = $brandService;
    }

    public function list(Request $request, $slug)
    {
        $category = $this->categoryService->getCategoryBySlug($slug);
        $products = $this->categoryService->getProductsByCategory($request, $category);
        // $childrenCategory = $this->getChildrenByParent($category);
        $categoryIds = $category->getAllChildrenIds();
        $productIds = $this->productService->getProductIdsByCategory($category);
        $allProducts = Product::whereIn('id', $productIds)->get();
        $brands = $this->brandService->getBrandsByProducts($allProducts);
        $childrenCategory = $this->categoryService->getCategoriesByIds($categoryIds);
        // $queries = $request->query();
        // dd($queries);
        $countProducts = $products->count();
        /* intval(): Chuyển thành số nguyên */
        $price = $this->categoryService->getProductPriceMinMaxByCategory($category);
        $priceMin = intval($price[0]);
        $priceMax = intval($price[1]);
        return Inertia::render('Customer/Category/List', [
            'products' => $products,
            'childrenCategory' => $childrenCategory,
            'category' => $category,
            'brands' => $brands,
            'countProducts' => $countProducts,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
        ]);
    }
}
