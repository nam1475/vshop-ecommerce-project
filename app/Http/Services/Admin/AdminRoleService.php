<?php

namespace App\Http\Services\Admin;

use Spatie\Permission\Models\Role;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\DB;

class AdminRoleService
{
    use HelperTrait;

    public function getRoleById($id)
    {
        return Role::find($id);
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function getRoleHasPermissions($id)
    {
        $role = $this->getRoleById($id)->load('permissions');
        return $role->permissions;
    }

    public function store($request)
    {
        try{
            DB::beginTransaction();
            $role = Role::create([
                'name' => $request->name,
            ]);
            $role->givePermissionTo($request->permissions);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create role: ' . $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        try{
            DB::beginTransaction();
            $role = $this->getRoleById($id);
            $role->update([
                'name' => $request->name
            ]);
            $role->syncPermissions($request->permissions);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update role: ' . $e->getMessage());
        }
    }

    public function destroy($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Role::class);
                return true;
            }
            $role = $this->getRoleById($id);
            $result = $role->delete();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete role: ' . $e->getMessage());
        }
    }
}