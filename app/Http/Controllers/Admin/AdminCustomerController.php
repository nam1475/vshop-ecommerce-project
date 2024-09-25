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
    public function list()
    {
        return Inertia::render('Admin/Customer/List', [
            'customers' => Customer::paginate(10)
        ]);
    }

    public function delete(Request $request, $id = null)
    {
        $result = $this->adminCustomerService->delete($request, $id);
        if($result){
            return redirect()->route('admin.customer.list')->with('success', 'Customer deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete customer.');

    }
}
