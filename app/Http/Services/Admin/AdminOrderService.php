<?php

namespace App\Http\Services\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\HelperTrait;
use App\Traits\Filterable;
use App\Traits\Searchable;

class AdminOrderService
{
    use HelperTrait, Filterable, Searchable;
    
    public function getOrders()
    {
        $query = Order::query();
        if($filters = request()->query('filter', [])) {
            // $query
            //     ->when($filters, function($q) use ($filters) {
            //         $q->whereIn('status', $filters);
            //     });
            $this->scopeFilters($query, $filters, Order::class);
        }
        if($search = request()->query('search')) {
            $this->scopeSearch($query, $search, Order::class);
        }
        return OrderResource::collection($query->paginate(3)->appends(request()->query()));
    }

    public function getOrderStatuses()
    {
        return Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
    }

    // public function getOrderStatus()
    // {
    //     return Order::pluck('status')->unique();
    // }

    public function update($request, $id)
    {
        try{
            $order = Order::find($id);
            $order->status = $request->status;
            $result = $order->save();
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function destroy($request, $id)
    {
        try{
            if($request->has('ids')) {
                $this->deleteRows($request, Order::class);
                return true;
            }
            $order = Order::find($id);
            $result = $order->delete();
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }


}