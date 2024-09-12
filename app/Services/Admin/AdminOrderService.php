<?php

namespace App\Services\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;

class AdminOrderService
{
    public function getOrders()
    {
        return OrderResource::collection(Order::paginate(10));
    }

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

    public function delete($id)
    {
        try{
            $order = Order::find($id);
            $result = $order->delete();
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }


}