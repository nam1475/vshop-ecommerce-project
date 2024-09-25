<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
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

    public function orderList(Request $request)
    {
        // dd($this->customerAccountService->getOrders());
        return Inertia::render('Customer/Account/Order/List', [
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

    public function storeAddress(Request $request)
    {
        $result = $this->customerAccountService->storeAddress($request);
        if($result){
            return redirect()->back()->with('success', 'Address added successfully.');
        }
        return redirect()->back()->with('error', 'Failed to add address.');
    }

    public function updateAddress(Request $request, $addressId)
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

    public function location()
    {
        return Inertia::render('Customer/Account/Location');
    }



    

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
