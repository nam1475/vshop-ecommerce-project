<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Services\Admin\AdminProductService;
use App\Traits\Images;
use Illuminate\Support\Facades\Mail;
use App\Mail\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\Checkout as NotificationsCheckout;
use App\Notifications\Welcome;
use Illuminate\Support\Facades\Notification;
use App\Traits\HelperTrait;

class CustomerHomeController extends Controller
{
    use Images, HelperTrait;

    protected $productService;

    public function __construct(AdminProductService $productService)
    {
        $this->productService = $productService;
    }

    public function home()
    {
        $products = ProductResource::collection($this->productService->getProducts()->paginate(4));
        // Mail::send(new Checkout());
        // $customer = auth('customer')->user();
        // $order = $this->getOrder(23)->resource;
        // Notification::send($customer, new NotificationsCheckout($order)); 

        return Inertia::render('Customer/Home', [
            'products' => $products,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
