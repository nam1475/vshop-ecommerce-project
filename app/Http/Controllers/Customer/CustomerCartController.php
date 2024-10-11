<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CartItem;
use App\Http\Services\Customer\CustomerAddressService;
use App\Http\Services\Customer\CustomerCartService;
use App\Traits\HelperTrait;

class CustomerCartController extends Controller
{

    use HelperTrait;

    protected $cartService;
    protected $addressService;

    public function __construct(CustomerCartService $cartService, CustomerAddressService $customerAddressService)
    {
        $this->cartService = $cartService;
        $this->addressService = $customerAddressService;
    }

    public function index()
    {
        return Inertia::render('Customer/Cart/Index', [
            'customerMainAddress' => $this->addressService->getCustomerMainAddress(),
        ]);
    }

    public function store(Request $request, $productId)
    {
        $result = $this->cartService->store($request, $productId);
        if($result) {
            return redirect()->back()->with('success', 'Product added to cart successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function orderAgain($id)
    {
        $result = $this->cartService->orderAgain($id);
        if($result) {
            return redirect()->back()->with('success', 'Cart updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
    
    public function update(Request $request, $cartId)
    {
        $result = $this->cartService->update($request, $cartId);
        if($result) {
            return redirect()->back()->with('success', 'Cart updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function destroy($cartId)
    {
        $result = $this->cartService->destroy($cartId);
        if($result) {
            return redirect()->back()->with('success', 'Product deleted from cart successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
    
}
