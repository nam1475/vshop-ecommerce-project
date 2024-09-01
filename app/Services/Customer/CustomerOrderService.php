<?php

namespace App\Services\Customer;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class CustomerOrderService
{
    public function store($request)
    {
        try{
            DB::beginTransaction();

            $customer = $request->user('customer');
            $carts = $request->carts;
            $products = $request->products;
            $totalPrice = $request->totalPrice;
            $address = $request->address;
            
            $mergeData = [];
            foreach($carts as $cart){
                foreach($products as $product){
                    if($cart['product_id'] == $product['id']){
                        $mergeData[] = array_merge($cart, ['name' => $product['name'], 'price' => $product['price']]);
                    }
                }
            }

            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $lineItems = [];
            foreach($mergeData as $item){
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item['name'],
                        ],
                        'unit_amount' => $item['price'] * 100,
                    ],
                    'quantity' => $item['quantity'],
                ];
            }

            /* Trường hợp thanh toán sau */
            if($request->sessionId){
                $sessionId = $stripe->checkout->sessions->retrieve($request->sessionId);
                return $sessionId->url;
            }

            $checkoutSession = $stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('customer.order.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.order.cancel'),
            ]);

            $mainAddress = $customer->customerAddress()->where('is_main', 1)->first();
            $mainAddress->update($address);
            $order = Order::create([
                'total_price' => $totalPrice, 
                'status' => 'pending', 
                'session_id' => $checkoutSession->id, 
                'customer_address_id' => $mainAddress->id
            ]);

            $cartItems = CartItem::where('customer_id', $customer->id)->get();
            foreach($cartItems as $cartItem){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price
                ]);
                $cartItem->delete();
            }

            Payment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'amount' => $totalPrice,
                'type' => 'stripe',
            ]);

            DB::commit();

            return $checkoutSession->url;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');
        try {
            $session = Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException();
                return false;
            }
            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                throw new NotFoundHttpException();
                return false;
            }
            if ($order->status == 'pending') {
                $order->status = 'success';
                $order->save();
            }
            return true;
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
            return false;
        }
    }
}