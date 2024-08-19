<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Services\Admin\AdminProductService;

class AdminProductController extends Controller
{   
    protected $productService;

    public function __construct(AdminProductService $productService)
    {
        $this->productService = $productService;
    }

    public function list()
    {
        $data = $this->productService->list();
        return Inertia::render('Admin/Product/List', [
            'products' => $data['products'], 
            'categories' => $data['categories'], 
            'brands' => $data['brands'],
        ]);
    }   

    public function add(){
        $data = $this->productService->list();
        return Inertia::render('Admin/Product/Add', [
            'products' => $data['products'], 
            'categories' => $data['categories'], 
            'brands' => $data['brands'],
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
        // dd($this->productService->getProductById($id));
        return Inertia::render('Admin/Product/Edit', [
            'product' => $this->productService->getProductById($id),
            'categories' => $this->productService->getCategories(),
            'brands' => $this->productService->getBrands(),
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

    public function delete($id){
        $result = $this->productService->delete($id);
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
