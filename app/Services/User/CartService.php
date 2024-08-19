<?php

namespace App\Services\User;

use App\Models\CartItem;

class CartService
{

    public function store($request, $productId)
    {
        try{
            $userId = auth()->user()->id;
            $findCartItem = CartItem::where(['product_id' => $productId, 'user_id' => $userId]);
            $quantity = $request->has('quantity') ? $request->quantity : 1;
            $cart = collect();
            if($findCartItem->exists()){
                $cart = $findCartItem->first()->update(['quantity' => $findCartItem->first()->quantity + $quantity]);
            }
            else{
                $cart = CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
            return $cart;
        } catch (\Exception $e) {
            throw new \Exception('Failed to add to cart: ' . $e->getMessage());
        }
    }

    public function update($request, $cartId)
    {
        try{
            $cart = CartItem::find($cartId);
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