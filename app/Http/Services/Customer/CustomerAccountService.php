<?php

namespace App\Http\Services\Customer;

use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerAccountService
{

    public function getOrders($request)
    {
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
            DB::beginTransaction();
            $customerAddresses = $this->getCustomerAddresses();
            $result = CustomerAddress::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'province' => $request->province['name'],
                'district' => $request->district['name'],
                'ward' => $request->ward['name'],
                'is_main' => $request->is_main,
                'customer_id' => $request->customer_id
            ]);
            foreach($customerAddresses as $address){
                if($result->is_main == 1 && $address->is_main == 1){
                    $address->update(['is_main' => 0]);
                }
            }
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return false;
        }
    }

    public function updateAddress($request, $addressId)
    {
        try{
            $customerAddresses = $this->getCustomerAddresses();
            foreach($customerAddresses as $address){
                /* Nếu lặp đến address hiện tại và chọn làm main */
                if($address->id == $addressId && $request->is_main == 1){
                    $address->update($request->all());
                }
                /* Nếu có 1 address đc chọn làm main mà đang có 1 address khác làm main thì sửa is_main của 
                address đó thành 0 */
                else if($request->is_main == 1 && $address->is_main == 1){
                    $address->update(['is_main' => 0]);
                }
                /* Nếu lặp đến address hiện tại mà ko chọn làm main thì chỉ update các trường khác và thoát 
                vòng lặp */
                else if($address->id == $addressId && $request->is_main == 0){
                    $address->update($request->all());
                    break;
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

