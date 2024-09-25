<?php
namespace App\Traits;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;

trait CartTrait{

    public function countCartItems()
    {
        $customer = auth('customer')->user();
        if($customer){
            // return CartItem::where('customer_id', $customer->id)->sum('quantity');
            return CartItem::where('customer_id', $customer->id)->count();
        }
    }

    public function getCartItems()
    {
        $customer = auth('customer')->user();
        if($customer){
            return CartItem::where('customer_id', $customer->id)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ];
            }); 
        }
    }

    public function getProductsAndCartItems(){
        // $cartItems = self::getCartItems();
        // $products = $cartItems->map(function ($item) {
        //     return Product::where('id', $item->product_id)->with('images')->first();
        // });
        // return [$products, $cartItems];

        $cartItems = $this->getCartItems();
        if(!empty($cartItems)){
            $ids = Arr::pluck($cartItems, 'product_id');
            $products = Product::whereIn('id', $ids)->with('images')->get();
            $cartItems = Arr::keyBy($cartItems, 'product_id');
            return [$products, $cartItems];
        }
        return [];
    }


    
}