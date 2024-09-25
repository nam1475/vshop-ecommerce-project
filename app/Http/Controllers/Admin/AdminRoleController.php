<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\AdminPermissionService;
use App\Http\Services\Admin\AdminRoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    protected $adminRoleService;
    protected $adminPermissionService;
    public function __construct(AdminRoleService $adminRoleService, AdminPermissionService $adminPermissionService)
    {
        $this->adminRoleService = $adminRoleService;
        $this->adminPermissionService = $adminPermissionService;
    }
    public function list()
    {
        return Inertia::render('Admin/Role/List', [
            'roles' => Role::with('permissions')->paginate(10),
        ]);
    }

    public function add()
    {
        return Inertia::render('Admin/Role/Add', [
            'permissions' => $this->adminPermissionService->getPermissions(),
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->adminRoleService->store($request);
        if($result){
            return redirect()->route('admin.role.list')->with('success', 'Role created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create role.');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Role/Edit', [
            'role' => $this->adminRoleService->getRoleById($id),
            'roleHasPermissions' => $this->adminRoleService->getRoleHasPermissions($id),
            'permissions' => $this->adminPermissionService->getPermissions(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->adminRoleService->update($request, $id);
        if($result){
            return redirect()->route('admin.role.list')->with('success', 'Role updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update role.');
    }

    public function delete(Request $request, $id = null)
    {
        $result = $this->adminRoleService->delete($request, $id);
        if($result){
            return redirect()->route('admin.role.list')->with('success', 'Role deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete role.');
    }
}
