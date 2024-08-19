<?php
namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;

class CartHelper{

    public static function countCartItems()
    {
        $user = auth()->user();
        if($user){
            // return CartItem::where('user_id', $user->id)->sum('quantity');
            return CartItem::where('user_id', $user->id)->count();
        }
    }

    public static function getCartItems()
    {
        $user = auth()->user();
        if($user){
            return CartItem::where('user_id', $user->id)->get()->map(function ($item) {
                if($item->product_id )
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ];
            }); 
        }
    }

    public static function getProductsAndCartItems(){
        // $cartItems = self::getCartItems();
        // $products = $cartItems->map(function ($item) {
        //     return Product::where('id', $item->product_id)->with('images')->first();
        // });
        // return [$products, $cartItems];

        $cartItems = self::getCartItems();
        if(!empty($cartItems)){
            $ids = Arr::pluck($cartItems, 'product_id');
            $products = Product::whereIn('id', $ids)->with('images')->get();
            $cartItems = Arr::keyBy($cartItems, 'product_id');
            return [$products, $cartItems];
        }
        return [];
    }


    
}