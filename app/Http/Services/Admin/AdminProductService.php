<?php

namespace App\Http\Services\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use App\Traits\HelperTrait;
use App\Traits\Filterable;
use App\Traits\Searchable;

class AdminProductService
{
    use HelperTrait, Filterable, Searchable;

    public function getProducts()
    {
        $query = Product::query();
        if($filters = request()->query('filter', [])) { 
            $this->scopeFilters($query, $filters, Product::class); 
        }
        if($search = request()->query('search')) {
            $this->scopeSearch($query, $search, Product::class);
        }
        return $query->with('category', 'brand', 'images');
    }
    
    public function getProductById($id)
    {
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
                $this->deleteRows($request, Product::class);
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