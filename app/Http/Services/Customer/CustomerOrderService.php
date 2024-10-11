<?php

namespace App\Http\Services\Customer;

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
use App\Mail\Checkout;
use App\Models\Customer;
use App\Notifications\Checkout as NotificationsCheckout;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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

            $stripe = new StripeClient(config('services.stripe.secret'));
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
                'status' => 'unpaid', 
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
                'status' => 'unpaid',
                'amount' => $totalPrice,
                'type' => 'stripe',
            ]);

            DB::commit();

            return $checkoutSession->url;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {
            DB::beginTransaction();

            Stripe::setApiKey(config('services.stripe.secret'));
            $sessionId = $request->get('session_id');
            $session = Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException();
                return false;
            }
            $order = Order::where('session_id', $session->id)->first();
            $orderItems = OrderItem::where('order_id', $order->id)->with('product')->get();
            /* Giảm số lượng product đi khi khách đặt hàng thành công */
            foreach ($orderItems as $item) {
                $item->product->decrement('quantity', $item->quantity);
            }
            
            if (!$order || !$order->payment) {
                throw new NotFoundHttpException();
                return false;
            }
            if ($order->status == 'unpaid') {
                $order->status = 'paided';
                $order->save();
            }
            if($order->payment->status == 'unpaid'){
                $order->payment->status = 'paided';
                $order->payment->save();
            }

            /* Gửi email */
            // Mail::to(auth('customer')->user()->email)->send(new Checkout());
            // Mail::mailer('mailgun')->send(new Checkout());
            // Mail::send(new Checkout());
            $customer = $request->user('customer');
            $customer->notify(new NotificationsCheckout($order));
            // Notification::send($customer, new NotificationsCheckout($order)); 
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // throw new NotFoundHttpException();
            throw new \Exception($e->getMessage());
            return false;
        }
    }
}