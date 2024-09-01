<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\HelperTrait;

class CustomerCategoryController extends Controller
{
    use HelperTrait;

    public function list(Request $request, $slug)
    {
        $category = $this->getCateogryBySlug($slug);
        $products = $this->getProductsByCategory($request, $category);
        $childrenCategory = $this->getChildrenByParent($category);
        // dd($products);
        // $childrenCategory = $this->getCategoriesByProducts($products, $category);
        $brands = $this->getBrandsByProducts($products);
        $countProducts = $products->count();
        return Inertia::render('Customer/Category/List', [
            'products' => $products,
            'childrenCategory' => $childrenCategory,
            'category' => $category,
            'brands' => $brands,
            'countProducts' => $countProducts
        ]);
    }
}
