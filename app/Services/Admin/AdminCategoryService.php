<?php

namespace App\Services\Admin;

use App\Models\Category;

class AdminCategoryService
{
    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function store($request)
    {
        try{
            $category = Category::create($request->all());
            return $category;

        } catch(\Exception $e){
            throw new \Exception('Failed to create category: ' . $e->getMessage());
        }
    }

    public function update($request, $id) 
    {
        try{
            $category = Category::find($id);
            $result = $category->update($request->all());
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to update category: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $category = Category::find($id);
            $result = $category->delete();
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to delete category: ' . $e->getMessage());
        }
    }
}