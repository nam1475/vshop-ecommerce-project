<?php

namespace App\Http\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Traits\HelperTrait;
use App\Traits\Filterable;
use App\Traits\Searchable;
use App\Traits\UploadAble;

class AdminProductService
{
    use HelperTrait, Filterable, Searchable, UploadAble;

    public function getProducts()
    {
        $query = Product::query()->withoutGlobalScope('published');
        if($filters = request()->query('filter', [])) { 
            $this->scopeFilters($query, $filters, Product::class); 
        }
        if($search = request()->query('search')) {
            $this->scopeSearch($query, $search, Product::class);
        }
        return $query->with('category', 'brand');
    }
    
    public function getProductById($id)
    {
        return Product::with('category', 'brand')->withoutGlobalScope('published')->find($id);
    }
    
    public function store($request)
    {
        try{
            DB::beginTransaction();
            $product = Product::create($request->all());
            if($request->hasFile('product_images')) {
                $productImages = $request->file('product_images');
                // $productImages->addMultipleMediaFromRequest('product_images')->toMediaCollection('product_images');
                $this->uploadMultiple($productImages, $product, 'product_images');
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
            $product = $this->getProductById($id);
            if($request->hasFile('product_images')) {
                $productImages = $request->file('product_images');
                $this->uploadMultiple($productImages, $product, 'product_images');
            }
            $product->update($request->all());
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Product::class);
                return true;
            }
            $product = Product::withoutGlobalScope('published')->find($id);
            $result = $product->delete();
            return $result;
        }catch(\Exception $e){
            throw new \Exception('Failed to delete product: ' . $e->getMessage());
        }
    }

    public function deleteImage($productId, $imageId)
    {
        try{
            $product = $this->getProductById($productId);
            $mediaItems = $product->getMedia('product_images');
            $result = $mediaItems->find($imageId)->delete();
            return $result;
        }catch(\Exception $e){
            throw new \Exception('Failed to delete product image: ' . $e->getMessage());
        }
    }
}