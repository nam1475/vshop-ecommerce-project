<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCategoryRequest;
use Illuminate\Http\Request;
use App\Http\Services\Admin\AdminCategoryService;
use Inertia\Inertia;
use App\Traits\HelperTrait;
use App\Traits\Images;

class AdminCategoryController extends Controller
{
    use HelperTrait, Images;

    protected $adminCategoryService;

    public function __construct(AdminCategoryService $adminCategoryService)
    {
        $this->adminCategoryService = $adminCategoryService;
        $this->middleware('permission:list category')->only(['list']);
        $this->middleware('permission:create category')->only(['create', 'store']);
        $this->middleware('permission:edit category')->only(['edit', 'update']);
        $this->middleware('permission:delete category')->only(['delete', 'deleteImage']);
    }

    public function index()
    {
        return Inertia::render('Admin/Category/Index', [
            'categories' => $this->adminCategoryService->getCategories()->paginate(1),
            'title' => 'Admin Categories',
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Category/Create', [
            'categories' => $this->adminCategoryService->getCategories()->get(),
            'title' => 'Admin Category Create',
        ]);
    }

    public function store(AdminCategoryRequest $request)
    {
        $result = $this->adminCategoryService->store($request);
        if($result){
            return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function edit($id)
    {
        $category = $this->adminCategoryService->getCategoryById($id);
        $image = $this->getImagesByModel($category, 'category_images');
        
        return Inertia::render('Admin/Category/Edit', [
            'category' => $category,
            'categories' => $this->adminCategoryService->getCategories()->get(),
            'image' => $image,
            'title' => 'Admin Category Edit',
        ]);
    }

    public function update(AdminCategoryRequest $request, $id)
    {
        $result = $this->adminCategoryService->update($request, $id);
        if($result){
            return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function destroy(Request $request, $id = null)
    {
        $result = $this->adminCategoryService->destroy($request, $id);
        if($result){
            return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function deleteImage($categoryId, $imageId){
        $result = $this->adminCategoryService->deleteImage($categoryId, $imageId);
        if($result){
            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete image.');
    }

    
}
