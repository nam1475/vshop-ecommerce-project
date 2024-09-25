<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Http\Services\Admin\AdminProductService;
use App\Traits\HelperTrait;
use App\Http\Services\Customer\CustomerBrandService;
use App\Models\Category;
use App\Http\Services\Admin\AdminCategoryService;

class AdminProductController extends Controller
{   
    use HelperTrait;

    protected $productService;
    protected $brandService;
    protected $categoryService;

    public function __construct(AdminProductService $productService, CustomerBrandService $brandService, AdminCategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->brandService = $brandService;
        $this->categoryService = $categoryService;
    }

    public function list()
    {
        $products = $this->productService->getProducts()->paginate(3);
        $allProducts = Product::all();
        $brands = $this->brandService->getBrandsByProducts($allProducts);
        $categories = $this->categoryService->getCategoriesByProducts($allProducts);
        return Inertia::render('Admin/Product/List', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }   

    public function add(){
        return Inertia::render('Admin/Product/Add', [
            'categories' => $this->categoryService->getCategories()->get(), 
            'brands' => Brand::all(),
        ]);
    }
    
    public function store(Request $request)
    {
        $result = $this->productService->store($request);
        if($result){
            return redirect()->route('admin.product.list')->with('success', 'Product created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create product.');
    }

    public function edit($id){
        return Inertia::render('Admin/Product/Edit', [
            'product' => $this->productService->getProductById($id),
            'categories' => $this->categoryService->getCategories()->get(),
            'brands' => Brand::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->productService->update($request, $id);
        if($result){
            return redirect()->route('admin.product.list')->with('success', 'Product updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update product.');
    }

    public function delete(Request $request, $id = null){
        $result = $this->productService->delete($request, $id);
        if($result){
            return redirect()->route('admin.product.list')->with('success', 'Product deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete product.');
    }

    public function deleteImage($id){
        $result = $this->productService->deleteImage($id);
        if($result){
            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete image.');
    }
}
