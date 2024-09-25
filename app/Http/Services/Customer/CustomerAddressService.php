<?php

namespace App\Http\Services\Customer;

use App\Models\CustomerAddress;

class CustomerAddressService
{
    public function getCustomerMainAddress()
    {
        return CustomerAddress::where([
            'customer_id' => auth('customer')->user()->id, 
            'is_main' => 1
        ])->first();
    }

}
