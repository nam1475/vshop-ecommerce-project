<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Admin\AdminBrandService;

class AdminBrandController extends Controller
{
    protected $brandService;

    public function __construct(AdminBrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function list()
    {
        return Inertia::render('Admin/Brand/List', [
            'brands' => $this->brandService->getBrands(),
        ]);
    }

    public function add()
    {
        return Inertia::render('Admin/Brand/Add');
    }

    public function store(Request $request)
    {
        $result = $this->brandService->store($request);
        if($result){
            return redirect()->route('admin.brand.list')->with('success', 'Brand created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create brand.');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Brand/Edit', [
            'brand' => $this->brandService->getBrandById($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->brandService->update($request, $id);
        if($result){
            return redirect()->route('admin.brand.list')->with('success', 'Brand updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update brand.');
    }

    public function delete(Request $request, $id = null)
    {
        $result = $this->brandService->delete($request, $id);
        if($result){
            return redirect()->route('admin.brand.list')->with('success', 'Brand deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete brand.');
    }   
}
