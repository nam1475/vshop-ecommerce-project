<?php

namespace App\Http\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Traits\HelperTrait;
use App\Traits\Searchable;
use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
use App\Traits\UploadAble;

class AdminCategoryService
{
    use HelperTrait, Searchable, Filterable, UploadAble;

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
            DB::beginTransaction();
            $category = Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            if($request->hasFile('category_image')) {
                $categoryImage = $request->file('category_image');
                $this->uploadOne($categoryImage, $category, 'category_images');
            }
            DB::commit();
            return true;
        } catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to create category: ' . $e->getMessage());
        }
    }

    public function update($request, $id) 
    {
        try{
            DB::beginTransaction();
            $category = $this->getCategoryById($id);
            $category->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            if($request->hasFile('category_image')) {
                $categoryImage = $request->file('category_image');
                $this->uploadOne($categoryImage, $category, 'category_images');
            }
            DB::commit();
            return true;
        } catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy($request, $id)
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

    public function deleteImage($categoryId, $imageId)
    {
        try{
            $category = $this->getCategoryById($categoryId);
            $mediaItems = $category->getMedia('category_images');
            $result = $mediaItems->find($imageId)->delete();
            return $result;
        } catch(\Exception $e){
            throw new \Exception('Failed to delete image: ' . $e->getMessage());
        }
    }
}