<?php

namespace App\Http\Services\Admin;

use App\Traits\HelperTrait;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserService
{
    use HelperTrait;

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function getUserHasRoles($id)
    {
        $user = $this->getUserById($id)->load('roles');
        return $user->roles;
    }

    public function store($request)
    {
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if($request->has('roles')) {
                $user->assignRole($request->roles);
            }
            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        try{
            DB::beginTransaction();
            $user = $this->getUserById($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update user: ' . $e->getMessage());
        }
    }

    public function delete($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, User::class);
                return true;
            }
            $user = $this->getUserById($id);
            $result = $user->delete();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete user: ' . $e->getMessage());
        }
    }

}