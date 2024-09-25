<?php

namespace App\Http\Services\Admin;

use Spatie\Permission\Models\Permission;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Session;

class AdminPermissionService
{
    use HelperTrait;

    public function getPermissionById($id)
    {
        return Permission::find($id);
    }

    public function getPermissions()
    {
        return Permission::all();
    }

    public function store($request)
    {
        try{
            $permission = $request->permission;
            $actions = $request->actions;
            foreach($actions as $action){
                $joinName = $action . ' ' . $permission;
                $isNameExist = Permission::where('name', $joinName)->exists();
                if(!$isNameExist){
                    Permission::create([
                        'name' => $joinName
                    ]);
                }
                else{
                    Session::flash('error', 'Permissions already exists');
                    return false;   
                }
            }
            return true;
        } catch(\Exception $e){
            throw new \Exception('Failed to create permission: ' . $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        try{
            $permission = $this->getPermissionById($id);
            $permission->update([
                'name' => $request->name
            ]);
            return true;
        } catch(\Exception $e){
            throw new \Exception('Failed to update permission: ' . $e->getMessage());
        }
    }

    public function delete($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Permission::class);
                return true;
            }
            $role = $this->getPermissionById($id);
            $result = $role->delete();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete role: ' . $e->getMessage());
        }
    }
}