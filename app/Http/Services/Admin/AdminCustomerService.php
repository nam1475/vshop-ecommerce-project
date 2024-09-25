<?php

namespace App\Http\Services\Admin;

use App\Models\Customer;
use App\Traits\HelperTrait;

class AdminCustomerService
{
    use HelperTrait;

    public function delete($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Customer::class);
                return true;
            }
            $customer = Customer::find($id);
            $result = $customer->delete();
            return $result;
        }catch(\Exception $e){
            throw new \Exception('Failed to delete customer: ' . $e->getMessage());
        }
    }

}