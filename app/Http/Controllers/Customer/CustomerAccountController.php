<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Customer\CustomerAddressRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Customer\CustomerAccountService;
use App\Traits\HelperTrait;

class CustomerAccountController extends Controller
{
    use HelperTrait;

    protected $customerAccountService;

    public function __construct(CustomerAccountService $customerAccountService)
    {
        $this->customerAccountService = $customerAccountService;
    }

    public function info()
    {
        return Inertia::render('Customer/Account/Info', [
            'title' => 'Account Information',
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->customerAccountService->changePassword($request);
        if($result){
            return redirect()->back()->with('success', 'Password changed successfully.');
        }
        return redirect()->back()->with('error', 'Failed to change password.');
    }

    public function updateAccountInfo(Request $request)
    {
        $result = $this->customerAccountService->updateAccountInfo($request);
        if($result){
            return redirect()->back()->with('success', 'Account info updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update account info.');
    }

    public function orderIndex(Request $request)
    {
        // dd($this->customerAccountService->getOrders());
        return Inertia::render('Customer/Account/Order/Index', [
            'orders' => $this->customerAccountService->getOrders($request)
        ]);
    }

    public function cancelOrder($orderId)
    {
        $result = $this->customerAccountService->cancelOrder($orderId);
        if($result){
            return redirect()->back()->with('success', 'Order cancelled successfully.');
        }
        return redirect()->back()->with('error', 'Failed to cancel order.');
    }

    public function orderDetails($orderId)
    {
        return Inertia::render('Customer/Account/Order/Details', [
            'order' => $this->getOrder($orderId)
        ]);
    }

    public function address()
    {
        return Inertia::render('Customer/Account/Address', [
            'customerAddresses' => $this->customerAccountService->getCustomerAddresses()
        ]);
    }

    public function storeAddress(CustomerAddressRequest $request)
    {
        $result = $this->customerAccountService->storeAddress($request);
        if($result){
            return redirect()->back()->with('success', 'Address added successfully.');
        }
        return redirect()->back()->with('error', 'Failed to add address.');
    }

    public function updateAddress(CustomerAddressRequest $request, $addressId)
    {
        $result = $this->customerAccountService->updateAddress($request, $addressId);
        if($result){
            return redirect()->back()->with('success', 'Address updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update address.');
    }

    public function deleteAddress($addressId)
    {
        $result = $this->customerAccountService->deleteAddress($addressId);
        if($result){
            return redirect()->back()->with('success', 'Address deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete address.');
    }


}
