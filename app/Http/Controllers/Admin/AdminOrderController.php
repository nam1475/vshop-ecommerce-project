<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Admin\AdminOrderService;
use App\Traits\HelperTrait;

class AdminOrderController extends Controller
{

    use HelperTrait;
    
    protected $adminOrderService;

    public function __construct(AdminOrderService $adminOrderService)
    {
        $this->adminOrderService = $adminOrderService;
    }

    public function list()
    {
        return Inertia::render('Admin/Order/List', [
            'orders' => $this->adminOrderService->getOrders()
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

    public function delete($id)
    {
        $result =  $this->adminOrderService->delete($id);
        if($result){
            return redirect()->back()->with('success', 'Order deleted successfully');
        }
        return redirect()->back()->with('error', 'Order deleted failed');
    }


}
