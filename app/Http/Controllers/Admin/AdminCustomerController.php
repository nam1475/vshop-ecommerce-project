<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Admin\AdminCustomerService;

class AdminCustomerController extends Controller
{
    protected $adminCustomerService;

    public function __construct(AdminCustomerService $adminCustomerService)
    {
        $this->adminCustomerService = $adminCustomerService;
    }
    public function index()
    {
        return Inertia::render('Admin/Customer/Index', [
            'customers' => Customer::paginate(10)
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Customer/Edit', [
            'customer' => $this->adminCustomerService->getCustomerById($id),
            'customerAddresses' => $this->adminCustomerService->getCustomerAddresses($id),
        ]);
    }

    public function destroy(Request $request, $id = null)
    {
        $result = $this->adminCustomerService->destroy($request, $id);
        if($result){
            return redirect()->route('admin.customer.index')->with('success', 'Customer deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete customer.');

    }
}
