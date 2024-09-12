<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\HelperTrait;

class AdminProductService
{
    use HelperTrait;

    public function getProducts()
    {
        $query = Product::query();
        if(request()->query()) {
            $brands = request()->query('filter');
            $query->whereHas('brand', function($q) use ($brands) {
                $q->whereIn('name', $brands);
            });        
        }
        return $query->with('category', 'brand', 'images');
        // return Product::with('category', 'brand', 'images');
    }

    public function getBrands()
    {
        return Brand::all();
    }
    
    public function getProductById($id)
    {
        // return Product::where('id', $id)->with('category', 'brand', 'images')->first();
        return Product::with('category', 'brand', 'images')->find($id);
    }

    public function store($request)
    {
        try{
            DB::beginTransaction();
            $product = Product::create($request->all());
            if($request->hasFile('product_images')) {
                $productImages = $request->file('product_images');
                foreach ($productImages as $image) {
                    $uniqueName = $this->uniqueImageName($image, 'product_images/');
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
    
    public function update($request, $id)
    {
        try{
            DB::beginTransaction();
            $product = Product::find($id);
            if($request->hasFile('product_images')) {
                $productImages = $request->file('product_images');
                foreach ($productImages as $image) {
                    $uniqueName = $this->uniqueImageName($image, 'product_images/');
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

    public function delete($request, $id)
    {
        try{
            if($request->has('ids')) {
                $products = Product::whereIn('id', $request->input('ids'))->get();
                foreach($products as $product){
                    $product->delete();
                }
                return true;
            }
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