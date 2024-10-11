<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminBrandRequest;
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

    public function index()
    {
        return Inertia::render('Admin/Brand/Index', [
            'brands' => $this->brandService->getBrands(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Brand/Create');
    }

    public function store(AdminBrandRequest $request)
    {
        $result = $this->brandService->store($request);
        if($result){
            return redirect()->route('admin.brand.index')->with('success', 'Brand created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create brand.');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Brand/Edit', [
            'brand' => $this->brandService->getBrandById($id),
        ]);
    }

    public function update(AdminBrandRequest $request, $id)
    {
        $result = $this->brandService->update($request, $id);
        if($result){
            return redirect()->route('admin.brand.index')->with('success', 'Brand updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update brand.');
    }

    public function destroy(Request $request, $id = null)
    {
        $result = $this->brandService->destroy($request, $id);
        if($result){
            return redirect()->route('admin.brand.index')->with('success', 'Brand deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete brand.');
    }   
}
