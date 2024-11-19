<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Customer\CustomerOrderService;

class CustomerOrderController extends Controller
{

    protected $customerOrderService;

    public function __construct(CustomerOrderService $customerOrderService)
    {
        $this->customerOrderService = $customerOrderService;
    }

    public function store(Request $request)
    {
        $result = $this->customerOrderService->store($request);
        if($result){
            return Inertia::location($result);
        }
        // return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        return redirect()->back();
    }

    public function success(Request $request)
    {
        $result = $this->customerOrderService->success($request);
        if($result){
            return redirect()->route('home');
        }
        return redirect()->back()->with('error', 'Failed to create order.');
    }

    public function cancel()
    {
        return redirect()->route('home');
    }

}
