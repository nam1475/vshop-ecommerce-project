<?php

namespace App\Http\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Traits\HelperTrait;
use App\Traits\Searchable;
use App\Traits\Filterable;

class AdminCategoryService
{
    use HelperTrait, Searchable, Filterable;

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function getCategories()
    {
        $query = Category::query();
        if($filters = request()->query('filter', [])) {
            $this->scopeFilters($query, $filters, Category::class);
        }
        if($search = request()->query('search')) {
            $this->scopeSearch($query, $search, Category::class);
        }
        return $query->with('childrenRecursive')->whereNull('parent_id');
    }

    public function getCategoriesByProducts($products)
    {
        return Category::whereIn('id', $products->pluck('category_id')->unique())->withCount('products')->get();
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

    public function delete($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Category::class);
                return true;
            }
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