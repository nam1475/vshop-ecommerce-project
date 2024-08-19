<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductService
{
    public function list()
    {
        $data = [
            'products' => $this->getProducts()->get(),
            'categories' => $this->getCategories(),
            'brands' => $this->getBrands(),
        ];
        return $data;
    }

    public function getProducts()
    {
        return Product::with('category', 'brand', 'images');
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function getBrands()
    {
        return Brand::all();
    }

    public function uniqueImageName($image)
    {
        $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = 'product_images/';
        $publicPath = public_path($path);
        $image->move($publicPath, $uniqueName);
        return $uniqueName;
    }

    public function store($request)
    {
        try{
            DB::beginTransaction();
            $product = Product::create($request->all());
            if($request->hasFile('product_images')) {
                $productImages = $request->file('product_images');
                foreach ($productImages as $image) {
                    $uniqueName = $this->uniqueImageName($image);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => '/product_images/' . $uniqueName,
                    ]);
                }
            }
            DB::commit();
            return $product;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to create product: ' . $e->getMessage());
        }
        
    }

    public function getProductById($id)
    {
        return Product::where('id', $id)->with('category', 'brand', 'images')->first();
    }
    
    public function update($request, $id)
    {
        try{
            DB::beginTransaction();
            $product = Product::find($id);
            if($request->hasFile('product_images')) {
                $productImages = $request->file('product_images');
                foreach ($productImages as $image) {
                    $uniqueName = $this->uniqueImageName($image);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => '/product_images/' . $uniqueName,
                    ]);
                }
            }
            $result = $product->update($request->all());
            DB::commit();
            return $result;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to update product: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $product = Product::find($id);
            $result = $product->delete();
            return $result;
        }catch(\Exception $e){
            throw new \Exception('Failed to delete product: ' . $e->getMessage());
        }
    }

    public function deleteImage($id)
    {
        try{
            $productImage = ProductImage::find($id);
            if(!empty($productImage->url)){
                // Xóa ảnh trong client 
                $publicPath = public_path($productImage->url);
                unlink($publicPath);
            }
            $result = $productImage->delete();
            return $result;
        }catch(\Exception $e){
            throw new \Exception('Failed to delete product image: ' . $e->getMessage());
        }
    }
}