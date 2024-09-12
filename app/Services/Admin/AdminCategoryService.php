<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Traits\HelperTrait;


class AdminCategoryService
{
    use HelperTrait;

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function store($request)
    {
        try{
            $category = Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            if($request->hasFile('image')) {
                $categoryImage = $request->file('image');
                $uniqueName = $this->uniqueImageName($categoryImage, 'category_images/');
                $path = '/category_images/' . $uniqueName;
                $category->update([
                    'url' => $path,
                ]);
            }
            return true;
        } catch(\Exception $e){
            throw new \Exception('Failed to create category: ' . $e->getMessage());
        }
    }

    public function update($request, $id) 
    {
        try{
            $category = $this->getCategoryById($id);
            $category->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            if($request->hasFile('image')) {
                $categoryImage = $request->file('image');
                $uniqueName = $this->uniqueImageName($categoryImage, 'category_images/');
                $path = '/category_images/' . $uniqueName;
                $category->update(['url' => $path]);
            }
            return true;
        } catch(\Exception $e){
            throw new \Exception('Failed to update category: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $category = $this->getCategoryById($id);
            $result = $category->delete();
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to delete category: ' . $e->getMessage());
        }
    }

    public function deleteImage($id)
    {
        try{
            $category = $this->getCategoryById($id);
            $publicPath = public_path($category->url);
            unlink($publicPath);
            $result = $category->update(['url' => null]);
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to delete image: ' . $e->getMessage());
        }
    }
}