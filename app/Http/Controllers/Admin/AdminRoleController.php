<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRoleRequest;
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
    public function index()
    {
        return Inertia::render('Admin/Role/Index', [
            'roles' => Role::with('permissions')->paginate(10),
            'title' => 'Admin Roles',
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Role/Create', [
            'permissions' => $this->adminPermissionService->getPermissions(),
            'title' => 'Admin Role Create',
        ]);
    }

    public function store(AdminRoleRequest $request)
    {
        $result = $this->adminRoleService->store($request);
        if($result){
            return redirect()->route('admin.role.index')->with('success', 'Role created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create role.');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Role/Edit', [
            'role' => $this->adminRoleService->getRoleById($id),
            'roleHasPermissions' => $this->adminRoleService->getRoleHasPermissions($id),
            'permissions' => $this->adminPermissionService->getPermissions(),
            'title' => 'Admin Role Edit',
        ]);
    }

    public function update(AdminRoleRequest $request, $id)
    {
        $result = $this->adminRoleService->update($request, $id);
        if($result){
            return redirect()->route('admin.role.index')->with('success', 'Role updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update role.');
    }

    public function destroy(Request $request, $id = null)
    {
        $result = $this->adminRoleService->destroy($request, $id);
        if($result){
            return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete role.');
    }
}
