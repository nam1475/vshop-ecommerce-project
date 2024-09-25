<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionName;
use App\Enums\TableName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPermissionRequest;
use App\Http\Services\Admin\AdminPermissionService;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPermissionController extends Controller
{
    
    protected $adminPermissionService;

    public function __construct(AdminPermissionService $adminPermissionService)
    {
        $this->adminPermissionService = $adminPermissionService;
    }
    public function list()
    {
        return Inertia::render('Admin/Permission/List', [
            'permissions' => Permission::paginate(10)
        ]);
    }

    public function add()
    {
        return Inertia::render('Admin/Permission/Add', [
            'tables' => TableName::cases(),
            'actions' => ActionName::cases()
        ]);
    }

    public function store(AdminPermissionRequest $request)
    {
        $result = $this->adminPermissionService->store($request);
        if($result){
            return redirect()->route('admin.permission.list')->with('success', 'Permission created successfully.');
        }
        return redirect()->route('admin.permission.list');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Permission/Edit', [
            'permission' => $this->adminPermissionService->getPermissionById($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->adminPermissionService->update($request, $id);
        if($result){
            return redirect()->route('admin.permission.list')->with('success', 'Permission updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update permission.');
    }

    public function delete(Request $request, $id = null)
    {
        $result = $this->adminPermissionService->delete($request, $id);
        if($result){
            return redirect()->route('admin.permission.list')->with('success', 'Permission deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete permission.');
    }


}
