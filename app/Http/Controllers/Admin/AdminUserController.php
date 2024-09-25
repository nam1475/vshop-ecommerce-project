<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\Admin\AdminRoleService;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Admin\AdminUserService;

class AdminUserController extends Controller
{
    protected $adminUserService;
    protected $adminRoleService;

    public function __construct(AdminUserService $adminUserService, AdminRoleService $adminRoleService)
    {
        $this->adminUserService = $adminUserService;
        $this->adminRoleService = $adminRoleService;
    }
    
    public function list()
    {
        return Inertia::render('Admin/User/List', [
            'users' => User::with('roles')->paginate(10)
        ]);
    }

    public function add()
    {
        return Inertia::render('Admin/User/Add', [
            'roles' => $this->adminRoleService->getRoles()
        ]);
    }

    public function store(RegisterRequest $request)
    {
        $result = $this->adminUserService->store($request);
        if($result){
            return redirect()->route('admin.user.list')->with('success', 'User created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create user.');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/User/Edit', [
            'user' => $this->adminUserService->getUserById($id),
            'roles' => $this->adminRoleService->getRoles(),
            'userHasRoles' => $this->adminUserService->getUserHasRoles($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->adminUserService->update($request, $id);
        if($result){
            return redirect()->route('admin.user.list')->with('success', 'User updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update user.');
    }   

    public function delete(Request $request, $id = null)
    {
        $result = $this->adminUserService->delete($request, $id);
        if($result){
            return redirect()->route('admin.user.list')->with('success', 'User deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete user.');
    }
}
