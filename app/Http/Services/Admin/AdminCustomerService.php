<?php

namespace App\Http\Services\Admin;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Traits\HelperTrait;

class AdminCustomerService
{
    use HelperTrait;

    public function getCustomerById($id)
    {
        return Customer::find($id);
    }

    public function getCustomerAddresses($customerId)
    {
        return CustomerAddress::where('customer_id', $customerId)->get();
    }

    public function destroy($request, $id)
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