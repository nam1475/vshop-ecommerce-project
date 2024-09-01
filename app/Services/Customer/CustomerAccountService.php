<?php

namespace App\Services\Customer;

use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Models\CustomerAddress;
use App\Models\Order;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class CustomerAccountService
{

    public function getOrders($request)
    {
        // $orders = Order::where('created_by', auth('customer')->user()->id)->paginate(10);
        $orders = Order::where('created_by', auth('customer')->user()->id);
        if($request->query()){
            $status = $request->query('status');
            $orders
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                });
        }
        return OrderResource::collection($orders->paginate(5)->withQueryString());
        // return $orders;
    }

    public function cancelOrder($orderId)
    {
        try{
            $order = Order::find($orderId);
            $order->status = 'cancelled';
            $order->save();
            return true;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function getOrder($orderId)
    {
        $order = Order::with(['orderItems.product.images', 'customerAddress.customer'])->where('id', $orderId)->first();
        return new OrderResource($order);
    }

    public function changePassword($request)
    {
        try{
            $customer = $request->user('customer');
            $result = $customer->update([
                'password' => Hash::make($request->newPassword)
            ]);
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function updateAccountInfo($request)
    {
        try{
            $customer = $request->user('customer');
            $result = $customer->update($request->all());
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function getCustomerAddresses()
    {
        return CustomerAddress::where('customer_id', auth('customer')->user()->id)->get();
    }

    public function storeAddress($request)
    {
        try{
            $customerAddresses = $this->getCustomerAddresses();
            $result = CustomerAddress::create($request->all());
            foreach($customerAddresses as $address){
                if($result->is_main == 1){
                    $address->update(['is_main' => 0]);
                }
            }
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function updateAddress($request, $addressId)
    {
        try{
            $customerAddresses = $this->getCustomerAddresses();
            foreach($customerAddresses as $address){
                if($address->id == $addressId && $request->is_main == 1){
                    $address->update($request->all());
                }
                else if($address->id == $addressId && $request->is_main == 0){
                    $address->update($request->all());
                    break;
                }
                else{
                    $address->update(['is_main' => 0]);
                }
            }
            // $result = CustomerAddress::find($addressId)->update($request->all());
            return true;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function deleteAddress($addressId)
    {
        try{
            $result = CustomerAddress::find($addressId)->delete();
            return $result;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    
}

