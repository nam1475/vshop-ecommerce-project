<?php

namespace App\Http\Middleware;

use App\Helpers\CartHelper;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Route;
use App\Traits\HelperTrait;
use App\Models\CustomerAddress;
use App\Http\Resources\CustomerAddressResource;

class HandleInertiaRequests extends Middleware
{
    use HelperTrait;

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'customer' => $request->user('customer'),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],

            'cart' => new CartResource(CartHelper::getProductsAndCartItems()),

            // 'customerAddress' => new CustomerAddressResource(CustomerAddress::where('customer_id', $request->user('customer')->id)->first()),
            
            'categories' => $this->getCategories()->get(),

            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ];
    }
}
