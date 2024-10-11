<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProductRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Http\Services\Admin\AdminProductService;
use App\Traits\HelperTrait;
use App\Http\Services\Customer\CustomerBrandService;
use App\Models\Category;
use App\Http\Services\Admin\AdminCategoryService;
use App\Traits\Images;

class AdminProductController extends Controller
{   
    use HelperTrait, Images;

    protected $productService;
    protected $brandService;
    protected $categoryService;

    public function __construct(AdminProductService $productService, CustomerBrandService $brandService, AdminCategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->brandService = $brandService;
        $this->categoryService = $categoryService;
        $this->middleware('permission:index product')->only(['index']);
        $this->middleware('permission:create product')->only(['create', 'store']);
        $this->middleware('permission:edit product')->only(['edit', 'update']);
        $this->middleware('permission:delete product')->only(['delete', 'deleteImage']);
    }

    public function index()
    {
        $products = $this->productService->getProducts()->paginate(3);
        $allProducts = Product::all();
        $brands = $this->brandService->getBrandsByProducts($allProducts);
        $categories = $this->categoryService->getCategoriesByProducts($allProducts);
        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }   

    public function create(){
        return Inertia::render('Admin/Product/Create', [
            'categories' => $this->categoryService->getCategories()->get(), 
            'brands' => Brand::all(),
        ]);
    }
    
    public function store(AdminProductRequest $request)
    {
        $result = $this->productService->store($request);               
        if($result){
            return redirect()->route('admin.product.index')->with('success', 'Product created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create product.');
    }

    public function edit($id){
        $product = $this->productService->getProductById($id);
        $images = $this->getImagesByModel($product, 'product_images');

        return Inertia::render('Admin/Product/Edit', [
            'product' => $this->productService->getProductById($id),
            'categories' => $this->categoryService->getCategories()->get(),
            'brands' => Brand::all(),
            'images' => $images,
        ]);
    }

    public function update(AdminProductRequest $request, $id)
    {
        $result = $this->productService->update($request, $id);
        if($result){
            return redirect()->route('admin.product.index')->with('success', 'Product updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update product.');
    }

    public function destroy(Request $request, $id = null){
        $result = $this->productService->destroy($request, $id);
        if($result){
            return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete product.');
    }
    
    public function deleteImage($productId, $imageId){
        $result = $this->productService->deleteImage($productId, $imageId);
        if($result){
            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete image.');
    }
}
