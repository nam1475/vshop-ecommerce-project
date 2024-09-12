<?php

namespace App\Services\Customer;

use App\Models\CartItem;
use App\Models\UserAddress;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;

class CustomerCartService
{
    public function store($request, $id)
    {
        try{
            $customerId = auth('customer')->user()->id;
            $cartItem = CartItem::where(['product_id' => $id, 'customer_id' => $customerId])->first();
            $quantity = $request->quantity;
            if($cartItem){
                // $cart = $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
                $cartItem->increment('quantity', $quantity);
            }
            else{
                CartItem::create([
                    'customer_id' => $customerId,
                    'product_id' => $id,
                    'quantity' => $quantity
                ]);
            }
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Failed to add to cart: ' . $e->getMessage());
        }
    }

    public function orderAgain($id)
    {
        try{
            DB::beginTransaction();
            $customerId = auth('customer')->user()->id;
            $result = Order::with(['orderItems.product.images', 'customerAddress.customer'])->where('id', $id)->first();
            $order = new OrderResource($result);
            
            CartItem::where('customer_id', $customerId)->delete();
            
            foreach($order->orderItems as $item){
                CartItem::create([
                    'customer_id' => $customerId,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to order again: ' . $e->getMessage());
        }

    }

    public function update($request, $cartId)
    {
        try{
            $cart = CartItem::find($cartId);
            // $quantity = $request->has('quantity') ? $request->quantity : $cart->quantity;
            $result = $cart->update(['quantity' => $request->quantity]);
            return $result; 
        } catch (\Exception $e) {
            throw new \Exception('Failed to update cart: ' . $e->getMessage());
        }
    }

    public function delete($cartId)
    {
        try{
            $cart = CartItem::find($cartId);
            $result = $cart->delete();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete from cart: ' . $e->getMessage());
        }
    }
}