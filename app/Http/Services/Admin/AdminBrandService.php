<?php

namespace App\Http\Services\Admin;

use App\Models\Brand;
use App\Traits\HelperTrait;
use App\Traits\Searchable;

class AdminBrandService
{
    use HelperTrait, Searchable;

    public function getBrandById($id)
    {
        return Brand::find($id);
    }

    public function getBrands()
    {
        $query = Brand::query();
        if($search = request()->query('search')) {
            $this->scopeSearch($query, $search, Brand::class);
        }
        return $query->paginate(10);
    }

    public function store($request)
    {
        try{
            $brand = Brand::create($request->all());
            return $brand;

        } catch(\Exception $e){
            throw new \Exception('Failed to create category: ' . $e->getMessage());
        }
    }

    public function update($request, $id) 
    {
        try{
            $brand = $this->getBrandById($id);
            $result = $brand->update($request->all());
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Brand::class);
                return true;
            }
            $brand = $this->getBrandById($id);
            $result = $brand->delete();
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to delete category: ' . $e->getMessage());
        }
    }
}