<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Traits\Images;
use Illuminate\Support\Facades\Mail;
use App\Mail\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\Checkout as NotificationsCheckout;
use App\Notifications\Welcome;
use Illuminate\Support\Facades\Notification;
use App\Traits\HelperTrait;
use App\Http\Services\Customer\CustomerProductService;

class CustomerHomeController extends Controller
{
    use Images, HelperTrait;

    protected $productService;

    public function __construct(CustomerProductService $productService)
    {
        $this->productService = $productService;
    }

    public function home()
    {
        $products = ProductResource::collection($this->productService->getProductsWithPagination());
        
        return Inertia::render('Customer/Home', [
            'products' => $products,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'title' => 'Home',
        ]);
    }
}
