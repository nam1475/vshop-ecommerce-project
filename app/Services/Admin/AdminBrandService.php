<?php

namespace App\Services\Admin;

use App\Models\Brand;

class AdminBrandService
{
    public function getBrandById($id)
    {
        return Brand::find($id);
    }

    public function getBrands()
    {
        return Brand::paginate(10);
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

    public function delete($id)
    {
        try{
            $brand = $this->getBrandById($id);
            $result = $brand->delete();
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to delete category: ' . $e->getMessage());
        }
    }
}