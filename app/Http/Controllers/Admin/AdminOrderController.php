<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\Admin\AdminOrderService;
use App\Traits\HelperTrait;

class AdminOrderController extends Controller
{

    use HelperTrait;
    
    protected $adminOrderService;

    public function __construct(AdminOrderService $adminOrderService)
    {
        $this->adminOrderService = $adminOrderService;
    }

    public function index()
    {
        return Inertia::render('Admin/Order/Index', [
            'orders' => $this->adminOrderService->getOrders(),
            'statuses' => $this->adminOrderService->getOrderStatuses(),
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Order/Edit', [
            'order' => $this->getOrder($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->adminOrderService->update($request, $id);
        if($result){
            return redirect()->back()->with('success', 'Order updated successfully');
        }
        return redirect()->back()->with('error', 'Order updated failed');
    }

    public function destroy(Request $request, $id = null)
    {
        $result =  $this->adminOrderService->destroy($request, $id);
        if($result){
            return redirect()->back()->with('success', 'Order deleted successfully');
        }
        return redirect()->back()->with('error', 'Order deleted failed');
    }


}
