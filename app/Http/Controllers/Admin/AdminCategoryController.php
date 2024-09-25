<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\AdminCategoryService;
use Inertia\Inertia;
use App\Traits\HelperTrait;

class AdminCategoryController extends Controller
{
    use HelperTrait;

    protected $adminCategoryService;

    public function __construct(AdminCategoryService $adminCategoryService)
    {
        $this->adminCategoryService = $adminCategoryService;
        $this->middleware('permission:list category')->only(['list']);
        $this->middleware('permission:add category')->only(['add', 'store']);
        $this->middleware('permission:edit category')->only(['edit', 'update']);
        $this->middleware('permission:delete category')->only(['delete', 'deleteImage']);
    }

    public function list()
    {
        return Inertia::render('Admin/Category/List', [
            'categories' => $this->adminCategoryService->getCategories()->paginate(1),
        ]);
    }

    public function add()
    {
        return Inertia::render('Admin/Category/Add', [
            'categories' => $this->adminCategoryService->getCategories()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->adminCategoryService->store($request);
        if($result){
            return redirect()->route('admin.category.list')->with('success', 'Category created successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Category/Edit', [
            'category' => $this->adminCategoryService->getCategoryById($id),
            'categories' => $this->adminCategoryService->getCategories()->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->adminCategoryService->update($request, $id);
        if($result){
            return redirect()->route('admin.category.list')->with('success', 'Category updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request, $id = null)
    {
        $result = $this->adminCategoryService->delete($request, $id);
        if($result){
            return redirect()->route('admin.category.list')->with('success', 'Category deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function deleteImage($id){
        $result = $this->adminCategoryService->deleteImage($id);
        if($result){
            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete image.');
    }

    
}
