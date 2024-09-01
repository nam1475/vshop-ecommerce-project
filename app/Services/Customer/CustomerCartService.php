<?php

namespace App\Services\Customer;

use App\Models\CartItem;
use App\Models\UserAddress;

class CustomerCartService
{
    public function store($request, $productId)
    {
        try{
            $customerId = auth('customer')->user()->id;
            $cartItem = CartItem::where(['product_id' => $productId, 'customer_id' => $customerId])->first();
            $quantity = $request->quantity;
            if($cartItem){
                // $cart = $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
                $cartItem->increment('quantity', $quantity);
            }
            else{
                $cartItem = CartItem::create([
                    'customer_id' => $customerId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
            return $cartItem;
        } catch (\Exception $e) {
            throw new \Exception('Failed to add to cart: ' . $e->getMessage());
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